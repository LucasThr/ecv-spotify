<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Artiste;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Security as UserSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
  #[Route('/account', name: 'app_my_account')]
  #[Security('is_granted("ROLE_USER")')]
  public function index(UserSecurity $security, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository): Response
  {
    /** @var User $user */
    $user = $security->getUser();

    $formBuilder = $this->createFormBuilder([
      'artiste' => $user->getArtist() ?? ''
    ]);
    $formBuilder->add('password', RepeatedType::class, [
      'type' => PasswordType::class,
      'required' => false,
      'first_options' => ['label' => 'mot de passe'],
      'second_options' => ['label' => 'répéter le mot de passe']
    ]);
    $formBuilder->add('artiste', TextType::class, ['label' => 'Nom d`artiste', 'required' => false]);
    $formBuilder->add('submit', SubmitType::class, ['label' => 'Mettre à jour']);

    $form = $formBuilder->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();
      $user->setPassword($userPasswordHasherInterface->hashPassword($user, $data['password']));

      if (!empty($data['password'])) {
        $user->setPassword($userPasswordHasherInterface->hashPassword($user, $data['password']));
      }

      if (!empty($data['artiste']) && $user->getArtist() === null) {
        $artiste = new Artist();
        $artiste->setName($data['artiste']);
        $user->setArtist($artiste);
      }

      $userRepository->flush();
    }

    return $this->render('my_account/index.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
