<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Commande;
use App\Repository\CategorieRepository;
use App\Repository\MenuRepository;
use App\Repository\ProductRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {

        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

       #[Route('/panier', name: 'panier')]
           public function panier(ProductRepository $productRepository, MenuRepository $menuRepository,CategorieRepository $categorieRepository, PanierService $panierService): Response
           {
               //$panierService->destroy();
               $plat=$categorieRepository->findOneBy(['nom'=>'PLATS']);
               $dessert=$categorieRepository->findOneBy(['nom'=>'DESSERTS']);
               $entree=$categorieRepository->findOneBy(['nom'=>'ENTREES']);
               $boisson=$categorieRepository->findOneBy(['nom'=>'BOISSONS']);
               $grillade=$categorieRepository->findOneBy(['nom'=>'GRILLADES']);
               $plats=$productRepository->findBy(['categorie'=>$plat]);
               $entrees=$productRepository->findBy(['categorie'=>$entree]);
               $desserts=$productRepository->findBy(['categorie'=>$dessert]);
               $boissons=$productRepository->findBy(['categorie'=>$boisson]);
               $grillades=$productRepository->findBy(['categorie'=>$grillade]);
               $menus=$menuRepository->findAll();

               $panier=$panierService->getFullPanier();
               $total=$panierService->getTotal();

               $panierMenu=$panierService->getFullPanierMenu();



               return $this->render('front/panier.html.twig', [
                   'boissons'=>$boissons,
                   'plats'=>$plats,
                   'desserts'=>$desserts,
                   'entrees'=>$entrees,
                   'menus'=>$menus,
                   'grillades'=>$grillades,
                   'panier'=>$panier,
                   'total'=>$total,
                   'panierMenu'=>$panierMenu
               ]);
           }


              #[Route('/retraitPanierMenu/{id}', name: 'retraitPanierMenu')]
                  public function retraitPanierMenu(PanierService $panierService, $id): Response
                  {

                      $panierService->retraitPanierMenu($id);


                      return $this->redirectToRoute('panier');
                  }


    #[Route('/ajoutPanier/{id}', name: 'ajoutPanier')]
    public function ajoutPanier(PanierService $panierService, $id): Response
    {
        $panierService->add($id);

        return $this->redirectToRoute('panier');
    }

    #[Route('/retraitPanier/{id}', name: 'retraitPanier')]
    public function retraitPanier(PanierService $panierService, $id): Response
    {
        $panierService->remove($id);


        return $this->redirectToRoute('panier');

    }


    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(PanierService $panierService, $id): Response
    {

        $panierService->delete($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/destroy', name: 'destroy')]
    public function destroy(PanierService $panierService): Response
    {
        $panierService->destroy();

        return $this->redirectToRoute('home');
    }


       #[Route('/ajoutMenuPanier/{param}/{idmenu}/{id}', name: 'ajoutMenuPanier')]
           public function ajoutMenuPanier(ProductRepository $productRepository, MenuRepository $menuRepository,CategorieRepository $categorieRepository, PanierService $panierService
       ,$param,$idmenu, $id): Response
           {

               $plat=$categorieRepository->findOneBy(['nom'=>'PLATS']);
               $dessert=$categorieRepository->findOneBy(['nom'=>'DESSERTS']);
               $entree=$categorieRepository->findOneBy(['nom'=>'ENTREES']);
               $boisson=$categorieRepository->findOneBy(['nom'=>'BOISSONS']);
               $grillade=$categorieRepository->findOneBy(['nom'=>'GRILLADES']);
               if ($param=='entree'){
                   $item=$productRepository->findBy(['categorie'=>$entree,'menu'=>1]);
                   //dd($item);
                   $param='plat';

                   return $this->render('front/ajoutMenuPanier.html.twig',['param'=>$param,'id'=>0,'idmenu'=>$idmenu,'items'=>$item]);

               }
               if ($param=='plat'){
                   $item=$productRepository->findBy(['categorie'=>$plat,'menu'=>1]);
                  $param='dessert';
                   $panierService->addMenuEntree($idmenu,$id);

                   return $this->render('front/ajoutMenuPanier.html.twig',['param'=>$param,'idmenu'=>$idmenu,$id=0,'items'=>$item]);

               }
               if ($param=='dessert'){
                   $item=$productRepository->findBy(['categorie'=>$dessert]);

                   $param='boissons';
                   $panierService->addMenuPlat($idmenu,$id);

                   return $this->render('front/ajoutMenuPanier.html.twig',['param'=>$param,'id'=>0,'idmenu'=>$idmenu,'items'=>$item]);
               }
               if ($param=='boissons'){
                   $item=$productRepository->findBy(['categorie'=>$boisson]);
                   $param='fin';
                   $panierService->addMenuDessert($idmenu,$id);

                   return $this->render('front/ajoutMenuPanier.html.twig',['param'=>$param,'id'=>0,'idmenu'=>$idmenu,'items'=>$item]);
               }
               if ($param=='boisson'){
                   $item=$productRepository->findBy(['categorie'=>$boisson, 'menu'=>0]);
                   $param='finBoisson';
                   return $this->render('front/ajoutMenuPanier.html.twig',['param'=>$param,'idmenu'=>$idmenu,'id'=>$id,'items'=>$item]);

               }
               if ($param=='fin'){
                   $panierService->addMenuBoissons($idmenu,$id);
                   return $this->redirectToRoute('panier');
               }
               if ($param=='finBoisson'){
                   $panierService->addMenuBoisson($idmenu,$id);
                   return $this->redirectToRoute('panier');
               }



               $grillades=$productRepository->findBy(['categorie'=>$grillade]);



               return $this->render('front/ajoutMenuPanier.html.twig', [

               ]);
           }






}


