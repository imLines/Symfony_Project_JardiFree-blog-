<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator){
        
    }
    
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();        
        return $this->redirect($url);
    }


    #[Route('/admin/users', name: 'admin_users')]
    public function manageUsers(): Response
    {
        $users = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        return $this->redirect($users);
    }

    #[Route('/admin/articles', name: 'admin_articles')]
    public function manageArticles(): Response
    {
        $articles = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($articles);
    }

    #[Route('/admin/categorys', name: 'admin_categorys')]
    public function manageCategorys(): Response
    {
        $categorys = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        return $this->redirect($categorys);
    }

    #[Route('/admin/comments', name: 'admin_comments')]
    public function manageComments(): Response
    {
        $comments = $this->adminUrlGenerator->setController(CommentCrudController::class)->generateUrl();
        return $this->redirect($comments);
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('JardiFree Management');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Manage Users', 'fa....', 'admin_users');
        yield MenuItem::linkToRoute('Manage Articles', 'fa....', 'admin_articles');
        yield MenuItem::linkToRoute('Manage Categorys', 'fa....', 'admin_categorys');
        yield MenuItem::linkToRoute('Manage Comments', 'fa....', 'admin_comments');
    }
}
