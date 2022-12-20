<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Menu;
use App\Entity\Product;
use App\Form\CategorieType;
use App\Form\EditProductType;
use App\Form\MenuType;
use App\Form\ProductType;
use App\Repository\CategorieRepository;
use App\Repository\MenuRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted('ROLE_ADMIN')]
class BackController extends AbstractController
{

    #[Route('/categorie', name: 'categorie')]
    #[Route('/editCategorie/{id}', name: 'editCategorie')]
    public function categorie(CategorieRepository $repository, EntityManagerInterface $manager, Request $request, $id = null): Response
    {

        $categories = $repository->findAll();
        if ($id) {
            $categorie = $repository->find($id);
        } else {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($categorie);
            $manager->flush();
            if ($id) {
                $this->addFlash('success', 'Catégorie modifié');
            } else {
                $this->addFlash('success', 'Catégorie ajoutée');
            }

            return $this->redirectToRoute('categorie');


        }

        return $this->render('back/categorie.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    #[Route('/deleteCategorie/{id}', name: 'deleteCategorie')]
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $manager): Response
    {
        $manager->persist($categorie);
        $manager->flush();
        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('categorie');

    }


    #[Route('/ajoutProduit', name: 'ajoutProduit')]
    public function ajoutProduct(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            if ($photo) {

                $photo_bdd = date('YmdHis') . uniqid() .
                    $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);

                $product->setPhoto($photo_bdd);
                $manager->persist($product);
                $manager->flush();

                $this->addFlash('success', 'Produit ajouté');
                return $this->redirectToRoute('gestionProduct');


            }

        }

        return $this->render('back/ajoutProduit.html.twig', [
            'form' => $form->createView()

        ]);
    }

    #[Route('/editProduct/{id}', name: 'editProduct')]
    public function editProduct(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(EditProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPhoto')->getData()) {
                $photo = $form->get('editPhoto')->getData();
                $photo_bdd = date('YmdHis') . uniqid() . $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $product->getPicture());
                $product->setPicture($photo_bdd);


            }
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Produit modifié');
            return $this->redirectToRoute('gestionProduct');
        }

        return $this->render('back/editProduct.html.twig', [
            'form'=>$form->createView(),
            'product'=>$product
        ]);
    }


    #[Route('/gestionProduct', name: 'gestionProduct')]
    public function gestionProduct(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('back/gestionProduct.html.twig', [
            'products' => $products
        ]);
    }


    #[Route('/deleteProduct/{id}', name: 'deleteProduct')]
    public function deleteProduct(Product $product, EntityManagerInterface $manager): Response
    {

        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'produit supprimé');


        return $this->redirectToRoute('gestionProduct');

    }

    #[Route('/ajoutMenu', name: 'ajoutMenu')]
    public function ajoutMenu(Request $request, EntityManagerInterface $manager,): Response
    {
        $menu = new Menu();

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($menu);
            $manager->flush();
            $this->addFlash('success', 'Menu ajouté');
            return $this->redirectToRoute('gestionMenu');

        }

        return $this->render('back/ajoutMenu.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/gestionMenu', name: 'gestionMenu')]
    public function gestionMenu(MenuRepository $Repository): Response
    {

        $menus=$Repository->findAll();

        return $this->render('back/gestionMenu.html.twig', [
            'menus'=>$menus
        ]);
    }

    #[Route('/editMenu/{id}', name: 'editMenu')]
    public function editMenu(Menu $editMenu,Request $request, EntityManagerInterface $manager,): Response
    {

        $form = $this->createForm(MenuType::class, $editMenu);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($editMenu);
            $manager->flush();
            $this->addFlash('success', 'Menu modifié');
            return $this->redirectToRoute('gestionMenu');

        }

        return $this->render('back/editMenu.html.twig', [
            'form' => $form->createView()
        ]);
    }



    #[Route('/deleteMenu/{id}', name: 'deleteMenu')]
           public function deleteMenu(Menu $menu, EntityManagerInterface $manager): Response
           {
               $manager->persist($menu);
               $manager->flush();
               $this->addFlash('success', 'Menu supprimé');
               return $this->redirectToRoute('gestionMenu');


           }







}





























































