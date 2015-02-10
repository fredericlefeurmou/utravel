<?php

namespace UTravel\UTravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use UTravel\AdminBundle\Entity\Pays;

use \Httpful\Request;
use \SimpleXMLElement;

class UTravelController extends Controller
{
    // -------------------- LOGIN -------------------- //

    /*
     *   L'URL utravel.utc.fr/login est utilisée comme service par le CAS UTC.
     *   1er appel : GET utravel.utc.fr/login sans parametres pour se connecter avec le CAS
     *   Connexion ...
     *   Redirige vers le site avec l'adresse utravel.utc.fr/login (methode GET) avec 'ticket' comme parametre
     *   Retrouver et sauvegarder le login en session. 
     */
    public function loginAction()
    {
        // Génère l'url de login, avec option redirect
        $redirectUrl = $this->getRequest()->query->get('redirect_url');
        $params = ($redirectUrl) ? array('redirect_url' => $redirectUrl) : array();
        
        $loginUrl = $this->generateUrl('utravel_login', $params, true); // true pour url absolue

        // Gestion de la session
        if ($this->getRequest()->getSession() == null)
        {
            $session = new Session();
            $session->start();
        }
        else { $session = $this->getRequest()->getSession(); }

        //
        if ($session->get('user_login_U7E2R', null) === null)
        {
            if($ticket = $this->getRequest()->query->get('ticket'))
            {
                // Ticket dans l'URL : la connexion a abouti, il faut récupérer le login
                try
                {
                    $userLogin = $this->checkCASReturn($ticket, $loginUrl);
                    $session->set('user_login_U7E2R', $userLogin);
                }
                catch(\UnexpectedValueException $e)
                {
                    return new Response($e->getMessage());
                }

                // Annuaire LDAP : contacter pour récupérer les valeurs de l'utilisateur
                /* Décommenter la ligne suivante pour tester en local, l'annuaire LDAP est interne au serveur UTC

                $results = array('dept' => 'ETU', 'nom' => 'Adeline', 'prenom' => 'Jonathan', 'email' => 'jonathan.adeline@etu.utc.fr');
                // Commenter la ligne suivante pour tester en local */
                $this->connectLdap($userLogin);

                $session->set('user_name_U7E2R', $results['nom']);
                $session->set('user_givenname_U7E2R', $results['prenom']);
                $session->set('user_email_U7E2R', $results['email']);

                // Détermination du role et des droits de l'utilisateur
                $special_admins = array("dcoqueux", "maupaslo", "oschoefs");
                if ($results["dept"] == "DRI" || in_array($userLogin, $special_admins))
                {
                    $token = new UsernamePasswordToken($userLogin, null, 'main', array('ROLE_ADMIN'));
                    $session->set('role_U7E2R', 'admin');
                }
                else
                {
                    $token = new UsernamePasswordToken($userLogin, null, 'main', array('ROLE_USER'));
                    $session->set('role_U7E2R', 'user');
                }
                $security = $this->container->get('security.context');
                $security->setToken($token);

                if (isset($params['redirect_url']))
                {
                    return $this->redirect($params['redirect_url']);
                }
                else
                {
                    return $this->redirect($this->generateUrl('utravel_opinion_homepage'));
                }
            }
            else
            {
                // Pas de ticket --> 1er appel : tentative de connexion
                return $this->redirect($this->container->getParameter('cas_login_url') . '?service=' . $loginUrl);
            }
        }
        else
        {
            if (isset($params['redirect_url']))
            {
                return $this->redirect($params['redirect_url']);
            }
            else
            {
                return $this->redirect($this->generateUrl('utravel_opinion_homepage'));
            }
        }
    }

    /* 
     *   Valide le ticket et recupere le login de l'utiliseur aussitot l'authentification réalisée.
     *   Sinon, le ticket n'est pas validé.
     */
    private function checkCASReturn($ticket, $service)
    {
        $validateUrl = $this->container->getParameter('cas_service_validate_url') . "?ticket=".urlencode($ticket)."&service=".urlencode($service);

        $request = Request::get($validateUrl)
          ->sendsXml()
          ->timeoutIn(10)
          ->send();

        $request->body = str_replace("\n", "", $request->body);
        try 
        {
            $xml = new SimpleXMLElement($request->body);
        }
        catch (\Exception $e) 
        {
            throw new \UnexpectedValueException("Return cannot be parsed : '{$request->body}'");
        }
        
        $namespaces = $xml->getNamespaces();
        
        $serviceResponse = $xml->children($namespaces['cas']);
        $user = $serviceResponse->authenticationSuccess->user;
        
        if ($user)
        {
            return (string)$user;
        }
        else 
        {
            $authFailed = $serviceResponse->authenticationFailure;
            if ($authFailed) 
            {
                $attributes = $authFailed->attributes();
                throw new \UnexpectedValueException($authFailed);
            }
            else 
            {
                throw new \UnexpectedValueException($request->body);
            }
        }
    }

    private function connectLdap($login)
    {
        $results = array('dept' => '', 'email' => '', 'prenom' => '', 'nom' => '');

        $server = "ldap.utc.fr";
        $port = 389;
        $ds = ldap_connect($server, $port);

        if ($ds)
        {
            // Connexion reussie
            $is_bind = ldap_bind($ds);
            if ($is_bind)
            {
                // Liaison initiee
                $base_dn = "ou=people,dc=utc,dc=fr";
                $filter = "uid=".$login;

                $nb_res = ldap_search($ds, $base_dn, $filter);
                $info = ldap_get_entries($ds, $nb_res);

                for ($i=0; $i<$info["count"]; $i++)
                {
                    $results['dept'] = array_key_exists("frutcdept", $info[$i]) ? 
                        $info[$i]["frutcdept"][0] : 'etu';
                    $results['email'] = $info[$i]["mail"][0];
                    $results['prenom'] = $info[$i]["givenname"][0];
                    $results['nom'] = $info[$i]["sn"][0];
                }

                ldap_close($ds);
                return $results;
            }
            else
            { throw new \UnexpectedValueException("Liaison echouee"); }
        }
        else
        { throw new \UnexpectedValueException("La connexion a echouee"); }
    }

    // -------------------- LOGOUT -------------------- //

    public function logoutAction()
    {
        // Le token de sécurité symfony est automatiquement supprimé
        $session = $this->getRequest()->getSession()->clear();

        $service = $this->generateUrl('utravel_login', array(), true);
        $logoutUrl = $this->container->getParameter('cas_logout_url') . "&service=".urlencode($service);

        $request = Request::get($logoutUrl)->send();
        return $this->redirect($this->generateUrl('utravel_opinion_homepage'));
    }
}
