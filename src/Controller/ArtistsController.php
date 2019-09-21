<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArtistsController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function displayList()
  {
    return $this->render('artistList.html.twig');
  }
}
