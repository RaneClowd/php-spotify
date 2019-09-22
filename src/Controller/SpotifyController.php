<?php
namespace App\Controller;

use SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{
  protected function getSpotifySession() {
    return new SpotifyWebAPI\Session(
        $_ENV['SPOTIFY_CLIENT_ID'],
        $_ENV['SPOTIFY_CLIENT_SECRET'],
        $_ENV['BASE_URL'] . $this->generateUrl('authRedirect')
      );
  }

  /**
   * @Route("/authorized", name="authRedirect")
   */
  public function authRedirect(Request $request, SessionInterface $session)
  {
    $code = $request->query->get("code");
    $spotifySession = $this->getSpotifySession();

    $spotifySession->requestAccessToken($code);
    $accessToken = $spotifySession->getAccessToken();

    $session->set('accessToken', $accessToken);
    return $this->redirectToRoute('home');
  }

  /**
   * @Route("/spotify", name="spotify")
   */
  public function spotifyRedirect(Request $request, SessionInterface $session)
  {
    $spotifySession = $this->getSpotifySession();
    return $this->redirect($spotifySession->getAuthorizeUrl([
      'scope' => [ 'user-top-read' ]
    ]));
  }
}
