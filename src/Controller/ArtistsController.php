<?php
namespace App\Controller;

use SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArtistsController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function displayList(Request $request)
  {
    $id = $_ENV['SPOTIFY_CLIENT_ID'];
    $secret = $_ENV['SPOTIFY_CLIENT_SECRET'];

    $session = new SpotifyWebAPI\Session(
        $id,
        $secret,
        'http://localhost:8000'
      );

    if ($request->query->has('code')) {
      $code = $request->query->get("code");
      $api = new SpotifyWebAPI\SpotifyWebAPI();

      $session->requestAccessToken($code);
      $api->setAccessToken($session->getAccessToken());
      $artists = $api->getMyTop('artists')->items;

      return $this->render('artistList.html.twig', [ 'artists' => $artists ] );
    } else {
      $url = $session->getAuthorizeUrl([
        'scope' => [ 'user-top-read' ]
      ]);
      return $this->redirect($url);
    }
  }
}
