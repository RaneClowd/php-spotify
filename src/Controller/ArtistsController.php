<?php
namespace App\Controller;

use SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArtistsController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function home(Request $request, SessionInterface $session)
  {
    // TODO: move logic into new
    if ($request->hasPreviousSession()) {
      if ($session->has('accessToken')) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($session->get('accessToken'));  // TODO: check to see if the token needs to be refreshed
        $artists = $api->getMyTop('artists')->items;

        return $this->render('artistList.html.twig', [ 'artists' => $artists ] );
      }
    } else {
      return $this->render('welcome.html.twig' );
    }
  }
}
