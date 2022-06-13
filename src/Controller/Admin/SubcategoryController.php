<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use App\Repository\SubcategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class SubcategoryController extends AbstractController
{
    #[Route('/new-sous-cat/{id}', name: 'new-sous-cat', methods: ['GET', 'POST'])]
    public function newSousCat(Request $request,Category $category, SubcategoryRepository $subcategoryRepository): Response
    {
        $subcategory = new Subcategory();
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subcategory->setParentCategory($category);
            $subcategoryRepository->add($subcategory, true);

            return $this->redirectToRoute('app_category_show', ['id' => $category->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/subcategory/new-sous-cat.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }

    #[Route('/sous-cat/{id}', name: 'details-sous-cat', methods: ['GET'])]
    public function showSousCat(Subcategory $subcategory): Response
    {
        // dd($subcategory);
        return $this->render('admin/subcategory/show-sous-cat.html.twig', [
            'subcategory' => $subcategory,
        ]);
    }

    #[Route('/edit-sous-cat/{id}', name: 'edit-sous-cat', methods: ['GET', 'POST'])]
    public function editSousCat(Request $request, Subcategory $subcategory, SubcategoryRepository $subcategoryRepository): Response
    {
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subcategoryRepository->add($subcategory, true);

            return $this->redirectToRoute('details-sous-cat', ['id' => $subcategory->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/subcategory/edit-sous-cat.html.twig', [
            'subcategory' => $subcategory,
            'form' => $form,
        ]);
    }

    #[Route('/delete-sous-cat/{id}', name: 'delete-sous-cat', methods: ['POST'])]
    public function deleteSousCat(Request $request, Subcategory $subcategory, SubcategoryRepository $subcategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subcategory->getId(), $request->request->get('_token'))) {
            $subcategoryRepository->remove($subcategory, true);
        }

        return $this->redirectToRoute('app_category_show', ['id' => $subcategory->getParentCategory()->getId()], Response::HTTP_SEE_OTHER);
    }
}
