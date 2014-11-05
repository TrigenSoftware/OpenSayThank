<?php
/*
    Copyright (C) TrigenSoftware, 2014

    This file is part of OpenSayThank.

    OpenSayThank is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    OpenSayThank is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with OpenSayThank.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Trigen\Bundle\ThankBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Trigen\Bundle\ThankBundle\Entity\Relation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Trigen\Bundle\ThankBundle\Entity\Thank;
use Trigen\Bundle\ThankBundle\Entity\Five;
use Buzz\Browser;

class ThankController extends Controller
{
    public function indexAction()
    {
        return $this->render('TrigenThankBundle:Thank:index.html.twig', array('mvc' => true));
    }

	public function previewAction($id)
    {
    	$thank = $this->apiThankData($id, true);
    	
    	if ($thank === null || $thank && isset($thank['error'])) return new RedirectResponse($this->generateUrl('trigen_user_login'));

        return $this->render('TrigenThankBundle:Thank:preview.html.twig', $thank);
    }


    public function apiAction(Request $request)
    {
    	if ($request->request->get("action") === "thanksCount") {
    		$response = $this->apiThanksCount();
    	} else 


    	if ($this->getUser() === null || !$this->getUser()->getVerified()) {
    		$response = array( 'error' => 'access denied' );
    	} else 


    	if ($request->request->get("action") === "userData") {
    		$response = $this->apiUserData($request->request->get("username"));
    	} else 

    	if ($request->request->get("action") === "currentUserData") {
    		$response = $this->apiCurrentUserData();
    	} else 


    	if ($request->request->get("action") === "searchUserWithData") {
    		$response = $this->apiSearchUserWithData(
    			$request->request->get("query"), 
    			$request->request->get("from")
    		);
    	} else 


    	if ($request->request->get("action") === "follow") {
    		$response = $this->apiFollow($request->request->get("username"));
    	} else 

    	if ($request->request->get("action") === "unfollow") {
    		$response = $this->apiUnfollow($request->request->get("username"));
    	} else 


    	if ($request->request->get("action") === "follows") {
    		$response = $this->apiFollowsWithData(
    			$request->request->get("username"),
    			$request->request->get("from")
    		);
    	} else 

    	if ($request->request->get("action") === "followers") {
    		$response = $this->apiFollowersWithData(
    			$request->request->get("username"),
    			$request->request->get("from")
    		);
    	} else 


    	if ($request->request->get("action") === "sayThank") {
    		$response = $this->apiSayThank(
    			$request->request->get("thankto"),
    			$request->request->get("body"),
    			$request->request->get("attachment")
    		);
    	} else 

    	if ($request->request->get("action") === "removeThank") {
    		$response = $this->apiRemoveThank($request->request->get("id"));
    	} else 

    	if ($request->request->get("action") === "thankIsRemoved") {
    		$response = $this->apiThankIsRemoved($request->request->get("id"));
    	} else 


    	if ($request->request->get("action") === "thanksWithData") {
    		$response = $this->apiThanksWithData(
    			$request->request->get("username"),
    			$request->request->get("from")
    		);
    	} else 

    	if ($request->request->get("action") === "saidThanksWithData") {
    		$response = $this->apiThanksWithData(
    			$request->request->get("username"),
    			$request->request->get("from"),
    			true
    		);
    	} else 

    	if ($request->request->get("action") === "gotThanksWithData") {
    		$response = $this->apiThanksWithData(
    			$request->request->get("username"),
    			$request->request->get("from"),
    			false
    		);
    	} else 


    	if ($request->request->get("action") === "giveFive") {
    		$response = $this->apiGiveFive($request->request->get("id"));
    	} else 

    	if ($request->request->get("action") === "removeFive") {
    		$response = $this->apiRemoveFive($request->request->get("id"));
    	} else 


    	if ($request->request->get("action") === "setInvited") {
    		$response = $this->apiSetInvited();
    	} else 


    	$response = array( 'error' => 'invalid request' );


    	if ($response === null) $response = array( 'error' => 'invalid request' );

    	return new JsonResponse($response);
    }


    public function apiThanksCount()
    {
    	$em = $this->getDoctrine()->getManager();

    	$count = $em
			->createQuery(
			   'SELECT COUNT(thank.id)
			    FROM TrigenThankBundle:Thank thank'
			)
			->getResult();

		return array( "count" => $count[0][1]*1);
    }


    private function apiCheckRelationFollow($username) 
    {
    	return $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Relation')
	    	->findOneBy(array(
	    		'username' => $this->getUser()->getUsername(),
	    		'follows'  => $username
	    	)) !== null;
    }

    private function apiCheckRelationFollower($username) 
    {
    	return $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Relation')
	    	->findOneBy(array(
	    		'username' => $username,
	    		'follows'  => $this->getUser()->getUsername()
	    	)) !== null;
    }


    private function apiCurrentUserData()
    {
    	$user = $this->getUser();

    	if ($user === null) return null;

    	$response = array(
    		"id"        => $user->getId(),
    		"username"  => $user->getUsername()
    	);

    	$profile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $response['username']));

	    $response = array_merge($response, array(
    		"photo"       => $user->getPhoto(),
    		"follows"     => $profile->getFollows(),
    		"followers"   => $profile->getFollowers(),
    		"gotThanks"   => $profile->getGotThanks(),
    		"saidThanks"  => $profile->getSaidThanks(),
    		"gotFives"    => $profile->getGotFives(),
    		"invited"     => $profile->getInvited(),
    		"vkontakteId" => $user->getVkontakteId()
    	));

    	// if ($response['follows']   === null) $response['follows']   = 0;
    	// if ($response['followers'] === null) $response['followers'] = 0;
    	// if ($response['gotFives']  === null) $response['gotFives']  = 0;
    	// if ($response['gotThanks'] === null) $response['gotThanks'] = 0;

    	return $response;
    }

    private function apiUserData($username)
    {
    	$user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:User')
	    	->findOneBy(array('username' => $username, 'verified' => true));

	    if ($user === null) return array( "error" => true, "username" => !!$user );

    	$profile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $username));

	    $response = array(
	    	"id"         => $user->getId(),
    		"username"   => $username,
    		"photo"      => $user->getPhoto(),
    		"follows"    => $profile->getFollows(),
    		"followers"  => $profile->getFollowers(),
    		"gotThanks"  => $profile->getGotThanks(),
    		"gotFives"   => $profile->getGotFives(),
    		"follow"     => $this->apiCheckRelationFollow($username),
    		"follower"   => $this->apiCheckRelationFollower($username)
    	);

    	// if ($response['follows']   === null) $response['follows']   = 0;
    	// if ($response['followers'] === null) $response['followers'] = 0;
    	// if ($response['gotFives']  === null) $response['gotFives']  = 0;
    	// if ($response['gotThanks'] === null) $response['gotThanks'] = 0;

    	return $response;
    }


    private function apiSetInvited()
    {
    	$profile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $this->getUser()->getUsername()));

	    if ($profile === null) return null;

	    $em = $this->getDoctrine()->getManager();

	    $profile->setInvited(true);

	    $em->persist($profile);
        $em->flush();

        return array( "success" => true );
    }


    private function apiSearchUser($query, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();

    	if ($from == null) return $em
			->createQuery(
			   'SELECT user.username
			    FROM TrigenUserBundle:User user
			    WHERE user.username != :username AND lower(user.username) LIKE :query AND user.verified = TRUE
			    ORDER BY user.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $this->getUser()->getUsername())
			->setParameter('query', "%".strtolower($query)."%")
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT user.username
			    FROM TrigenUserBundle:User user
			    WHERE user.username != :username AND lower(user.username) LIKE :query AND user.id < :frm AND user.verified = TRUE
			    ORDER BY user.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $this->getUser()->getUsername())
			->setParameter('query', "%".strtolower($query)."%")
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT user.username
			    FROM TrigenUserBundle:User user
			    WHERE user.username != :username AND lower(user.username) LIKE :query AND user.id > :frm AND user.verified = TRUE
			    ORDER BY user.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $this->getUser()->getUsername())
			->setParameter('query', "%".strtolower($query)."%")
			->setParameter('frm', $from)
			->getResult();
    }

    private function apiSearchUserWithData($query, $from = null)
    {
    	$users = $this->apiSearchUser($query, $from);
    	$res = array();

    	foreach ($users as $user) {
    		$tmp = $this->apiUserData($user['username']);
    		if ($tmp) $res[] = $tmp;
    	}

    	return $res;
    }


    private function apiFollow($username) {
    	$user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:User')
	    	->findOneBy(array('username' => $username, 'verified' => true));

	    $currentUser = $this->getUser();

	    if ($user === null || $this->apiCheckRelationFollow($username) || $username == $currentUser->getUsername()) 
	    	return array( 
	    		"error" => true, 
	    		"username" => !!$user, 
	    		"notFollow" => !$this->apiCheckRelationFollow($username),
	    		"notSelfFollow" => $username != $currentUser->getUsername()
	    	);

	    $profile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $username));

	    $currentProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUser->getUsername()));

	    // insert row into relations
	    // currentuser.follows ++
	    // otheruser.followers ++
	    
	    $em = $this->getDoctrine()->getManager();

	    $relation = new Relation();
	    $relation->setTime(new \DateTime(null, new \DateTimeZone("GMT")));
	    $relation->setUsername($currentUser->getUsername());
	    $relation->setFollows($username);

	    $em->persist($relation);
        $em->flush();

        $currentProfile->setFollows($currentProfile->getFollows() + 1);

        $em->persist($currentProfile);
        $em->flush();

        $profile->setFollowers($profile->getFollowers() + 1);

        $em->persist($profile);
        $em->flush();

        return array( "success" => true );
    }

    private function apiUnfollow($username) {
    	$user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:User')
	    	->findOneBy(array('username' => $username, 'verified' => true));

	    if ($user === null || !$this->apiCheckRelationFollow($username)) 
	    	return array(
	    		"error" => true,
	    		"username" => !!$user,
	    		"follow" => $this->apiCheckRelationFollow($username)
	    	);

	    $profile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $username));

	    $currentUser = $this->getUser();
	    $currentProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUser->getUsername()));

	    // remove row from relations
	    // currentuser.follows --
	    // otheruser.followers --
	    
	    $em = $this->getDoctrine()->getManager();

	    $relation = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Relation')
	    	->findOneBy(array(
	    		'username' => $currentUser->getUsername(),
	    		'follows'  => $username
	    	));

	    $em->remove($relation);
        $em->flush();

        $currentProfile->setFollows($currentProfile->getFollows() - 1);

        $em->persist($currentProfile);
        $em->flush();

        $profile->setFollowers($profile->getFollowers() - 1);

        $em->persist($profile);
        $em->flush();

        return array( "success" => true );
    }


    private function apiFollows($username, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();

		if ($from == null) return $em
			->createQuery(
			   'SELECT relation.id, relation.follows AS username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.username = :username
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT relation.id, relation.follows AS username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.username = :username AND relation.id < :frm
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT relation.id, relation.follows AS username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.username = :username AND relation.id > :frm
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', $from)
			->getResult();
    }

    private function apiFollowers($username, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();
    	
		if ($from == null) return $em
			->createQuery(
			   'SELECT relation.id, relation.username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.follows = :username
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT relation.id, relation.username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.follows = :username AND relation.id < :frm
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT relation.id, relation.username
			    FROM TrigenThankBundle:Relation relation
			    WHERE relation.follows = :username AND relation.id > :frm
			    ORDER BY relation.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', $from)
			->getResult();
    }


    private function apiFollowsWithData($username, $from = null)
    {
    	$users = $this->apiFollows($username, $from);
    	$res = array();

    	foreach ($users as $user) {
    		$tmp = $this->apiUserData($user['username']);
    		if ($tmp) {
    			$tmp['id'] = $user['id'];
    			$res[] = $tmp;
    		}
    	}

    	return $res;
    }

    private function apiFollowersWithData($username, $from = null)
    {
    	$users = $this->apiFollowers($username, $from);
    	$res = array();

    	foreach ($users as $user) {
    		$tmp = $this->apiUserData($user['username']);
    		if ($tmp) {
    			$tmp['id'] = $user['id'];
    			$res[] = $tmp;
    		}
    	}

    	return $res;
    }


    private function apiSayThank($thankto, $body, $attachment = false)
    {
    	$user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:User')
	    	->findOneBy(array('username' => $thankto, 'verified' => true));

	    if ($user === null || !preg_match("/^(.){3,100}$/", $body) 
	    	|| $attachment && !preg_match("/^data:image\/([\w]+);/", $attachment) ) 
	    	return array( 
	    		"error" => true, 
	    		"user" => !!$user, 
	    		"body" => preg_match("/^(.){3,100}$/", $body), 
	    		"attachment" => !$attachment || preg_match("/^data:image\/([\w]+);/", $attachment)
	    	);

	    $em = $this->getDoctrine()->getManager();

	    $userProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $thankto));

	    $userProfile->setGotThanks($userProfile->getGotThanks() + 1);
	    $em->persist($userProfile);
        $em->flush();

        $currentUsername = $this->getUser()->getUsername();
        $currentUserProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUsername));

        $currentUserProfile->setSaidThanks($currentUserProfile->getSaidThanks() + 1);
        $em->persist($currentUserProfile);
        $em->flush();

	    $thank = new Thank();
	    $thank->setTime(new \DateTime(null, new \DateTimeZone("GMT")));
	    $thank->setUsername($currentUsername);
	    $thank->setThankto($thankto);
	    $thank->setBody($body);
	    $thank->setRichBody($this->handleBody($body));

	    if ($attachment) {
	    	$attachmentName = $this->saveImage($attachment);
	    	$thank->setAttachment($attachmentName);
	    	$thank->setAttachmentUrl($this->compressUrl("http://saythank.me/attachments/".$attachmentName));
	    }

	    $em->persist($thank);
        $em->flush();

        return array( "success" => true );
    }

    private function apiRemoveThank($id)
    {
    	$currentUsername = $this->getUser()->getUsername();
    	$thank = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('id' => $id, 'username' => $currentUsername));

	    if ($thank === null) return array(
    		"error" => true,
    		"thank" => !!$thank
    	);

	    $em = $this->getDoctrine()->getManager();

	    $userProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $thank->getThankto()));

	    $userProfile->setGotThanks($userProfile->getGotThanks() - 1);
	    $em->persist($userProfile);
        $em->flush();

        $currentUserProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUsername));

        $currentUserProfile->setSaidThanks($currentUserProfile->getSaidThanks() - 1);
        $em->persist($currentUserProfile);
        $em->flush();

	    $attachment = $thank->getAttachment();

	    $em->remove($thank);
        $em->flush();

        if ($attachment) 
	    	$this->removeImage($attachment);

        return array( "success" => true );
    }

    private function apiThankIsRemoved($id)
    {
    	return array("removed" => $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('id' => $id)) === null);
    }

    private function handleBody($body)
    {
    	$body = htmlspecialchars($body);

    	$body = preg_replace(
    		"/((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w\.-]*)*\/?)/",
    		'<a href="$1" target="_blank">$1</a>', 
    		$body
    	);

		return $body;
    }

    private function saveImage($dataUrl) 
    {
    	preg_match("/^data:image\/([\w]+);/", $dataUrl, $format);
    	$filename = md5($dataUrl).".".$format[1];
    	$file = fopen($this->get('kernel')->getRootDir()."/../web/attachments/$filename", "wb"); 
	    $dataUrl = explode(',', $dataUrl);

	    fwrite($file, base64_decode($dataUrl[1])); 
	    fclose($file); 

	    return $filename;
    }

    private function compressUrl($url)
    {
    	$browser = new Browser();
		$response = $browser->get('http://po.st/api/shorten?longUrl='.rawurlencode($url).'&apiKey=0411652B-9C21-4B86-8EFA-80AE3D021A13&format=txt');

		return $response->getContent();
    }

    private function removeImage($attachment)
    {
    	$thank = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('attachment' => $attachment));

	    if ($thank !== null) return null;

	    return unlink($this->get('kernel')->getRootDir()."/../web/attachments/$attachment");
    }


    private function apiThankData($id, $withFlatBody = false)
    {
    	$thank = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('id' => $id));

	    if ($thank === null) return array(
    		"error" => true,
    		"thank" => !!$thank
    	);

	    $user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:User')
	    	->findOneBy(array('username' => $thank->getUsername()));

	    $response = array(
	    	"id"         => $thank->getId(),
	    	"time"       => $thank->getTime()->format("D, d M Y H:i:s")." GMT",
    		"username"   => $thank->getUsername(),
    		"photo"      => $user->getPhoto(),
    		"thankto"    => $thank->getThankto(),
    		"body"       => $thank->getRichBody(),
    		"attachment" => $thank->getAttachment(),
    		"vkShare"    => "http://vk.com/share.php?url=http://saythank.me/preview/".$thank->getId(),
    		"twShare"    => "https://twitter.com/intent/tweet?url=http%3A%2F%2Fsaythank.me&text="
    						.rawurlencode($thank->getBody()
    						.($thank->getAttachmentUrl() ? " ".$thank->getAttachmentUrl() : ""))
    						."&hashtags=saythank",
    		"gotFives"   => $thank->getGotFives(),
    		"fived"      => $this->getUser() && !!$this->getDoctrine()
		    	->getRepository('TrigenThankBundle:Five')
		    	->findOneBy(array(
		    		'username' => $this->getUser()->getUsername(),
		    		'thankId' => $thank->getId() 
		    	))     
    	);

    	if ($withFlatBody)
    		$response["flatBody"] = $thank->getBody();

    	return $response;
    }


    private function apiThanks($username, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();

		if ($from == null) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.username = :username OR thank.thankto = :username
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE ( thank.username = :username OR thank.thankto = :username ) AND thank.id < :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE ( thank.username = :username OR thank.thankto = :username ) AND thank.id > :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', $from)
			->getResult();
    }

    private function apiSaidThanks($username, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();

		if ($from == null) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.username = :username
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.username = :username AND thank.id < :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.username = :username AND thank.id > :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', $from)
			->getResult();
    } 

    private function apiGotThanks($username, $from = null)
    {
    	$em = $this->getDoctrine()->getManager();

		if ($from == null) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.thankto = :username
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->getResult();

		else 

		if ($from < 0) return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.thankto = :username AND thank.id < :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', abs($from))
			->getResult();

		else return $em
			->createQuery(
			   'SELECT thank.id
			    FROM TrigenThankBundle:Thank thank
			    WHERE thank.thankto = :username AND thank.id > :frm
			    ORDER BY thank.id DESC'
			)
			->setMaxResults(15)
			->setParameter('username', $username)
			->setParameter('frm', $from)
			->getResult();
    } 

    private function apiThanksWithData($username, $from = null, $filter = null)
    {
    	if ($filter === true) {
    		$thanks = $this->apiSaidThanks($username, $from);
    	} else

    	if ($filter === false) {
    		$thanks = $this->apiGotThanks($username, $from);
    	} 

    	else {
    		$thanks = $this->apiThanks($username, $from);
    	}

    	$res = array();

    	foreach ($thanks as $thank) {
    		$tmp = $this->apiThankData($thank['id']);
    		if ($tmp) $res[] = $tmp;
    	}

    	return $res;
    }


    private function apiGiveFive($id)
    {
    	$thank = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('id' => $id));

	    $currentUsername = $this->getUser()->getUsername();

	    if ($thank === null || $thank->getUsername() == $currentUsername) 
	    	return array(
	    		"error" => true,
	    		"thank" => !!$thank,
	    		"notSelfFive" => $thank->getUsername() != $currentUsername
	    	);

	    $em = $this->getDoctrine()->getManager();

	    $five = new Five();
	    $five->setUsername($currentUsername);
	    $five->setThankId($id);
	    $five->setTime(new \DateTime(null, new \DateTimeZone("GMT")));

	    $em->persist($five);
        $em->flush();

        $thank->setGotFives($thank->getGotFives() + 5);

        $em->persist($thank);
        $em->flush();

        $currentUserProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUsername));
        $currentUserProfile->setGaveFives($currentUserProfile->getGaveFives() + 1);

        $em->persist($currentUserProfile);
        $em->flush();

        $user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $thank->getUsername()));
	    $user->setGotFives($user->getGotFives() + 1);

        $em->persist($user);
        $em->flush();

        return array( "success" => true );
    }

    private function apiRemoveFive($id)
    {
	    $currentUsername = $this->getUser()->getUsername();

    	$five = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Five')
	    	->findOneBy(array('username' => $currentUsername, 'thankId' => $id));

	    if ($five === null) 
	    	return array(
	    		"error" => true,
	    		"five" => !!$five
	    	);

	    $em = $this->getDoctrine()->getManager();

        $thank = $this->getDoctrine()
	    	->getRepository('TrigenThankBundle:Thank')
	    	->findOneBy(array('id' => $id));
        $thank->setGotFives($thank->getGotFives() - 5);

        $em->persist($thank);
        $em->flush();

        $currentUserProfile = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $currentUsername));
        $currentUserProfile->setGaveFives($currentUserProfile->getGaveFives() - 1);

        $em->persist($currentUserProfile);
        $em->flush();

        $user = $this->getDoctrine()
	    	->getRepository('TrigenUserBundle:Profile')
	    	->findOneBy(array('username' => $thank->getUsername()));
	    $user->setGotFives($user->getGotFives() - 1);

        $em->persist($user);
        $em->flush();

        $em->remove($five);
        $em->flush();

        return array( "success" => true );
    }


    public function testApiAction()
    {
    	$browser = new Browser();
		$response = $browser->get('http://po.st/api/shorten?longUrl=http%3A%2F%2Fradiumone.com%2F&apiKey=0411652B-9C21-4B86-8EFA-80AE3D021A13&format=txt');
		var_dump($this->apiThanksCount());
    	return new Response($response->getContent());
    }
}
