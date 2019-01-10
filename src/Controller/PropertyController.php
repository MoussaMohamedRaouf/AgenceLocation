<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;

use App\Notification\ContactNotification;
use App\Entity\Contact;
use App\Form\ContactType;


class PropertyController extends AbstractController
{
  /**
   * @var PropertyRepository
   */
  private $repository;

  public function __construct(ObjectManager $em, PropertyRepository $repository)
  {
    $this->repository = $repository;
    $this->em = $em;
  }


  /**
   * @Route("/biens", name="property.index")
   * @return Response
   */
  public function index(PaginatorInterface $paginator, Request $request) : Response
  {
    $search = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class, $search);
    $form->handleRequest($request);

    $properties = $paginator->paginate(
      $this->repository->findAllVisibleQuery($search),
      $request->query->getInt('page', 1),
      12
    );

    return $this->render('property/index.html.twig', [
      'current_menu' => 'properties',
      'properties' => $properties,
      'form' => $form->createView(),
    ]);


  }

  /**
   * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
   */
  public function show(ContactNotification $notification, Property $property, string $slug, Request $request) 
  {


    if ($property->getSlug() !== $slug) {
      return $this->redirectToRoute('property.show', [
        'id' => $property->getId(),
        'slug' => $property->getSlug()
      ], 301);
    }

    $contact = new Contact();
    $contact->setProperty($property);
    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $notification->notify($contact);
      $this->addFlash('success', 'Votre email a ete bien envoyer.');
      return $this->redirectToRoute('property.show', [
        'id' => $property->getId(),
        'slug' => $property->getSlug()
      ]);

    }


    return $this->render('property/show.html.twig', [
      'property' => $property,
      'current_menu' => 'properties',
      'form' => $form->createView(),
    ]);
  }
}
