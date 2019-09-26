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
    // TODO: move logic into new service
    if ($request->hasPreviousSession()) {
      if ($session->has('accessToken')) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($session->get('accessToken'));  // TODO: check to see if the token needs to be refreshed
        $artists = $api->getMyTop('artists')->items;
        
        return $this->render('artistList.html.twig', [ 'artists' => $artists ]);
      }
    } else {
      return $this->render('welcome.html.twig' );
    }
  }

  /**
   * @Route("/artist/{artistId}", name="artistDetails")
   */
  public function artistDetails(Request $request, string $artistId, SessionInterface $session) {
    $api = new SpotifyWebAPI\SpotifyWebAPI();
    $api->setAccessToken($session->get('accessToken'));
    $tracks = $api->getArtistTopTracks($artistId, [ "country" => "US" ])->tracks;
    var_dump($tracks[0]->album->artists[0]); // TODO: show the artists image in the background of the page
    return $this->render('artistDetails.html.twig', [ 'tracks' => $tracks ]);
  }
}
