<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Cliente;
use \Symfony\Component\HttpFoundation\Request;
use App\Form\CrearClienteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ClienteController extends AbstractController {

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/cliente",name="listado_clientes")
     */
    public function index(ManagerRegistry $doctrine): Response {


        $repositorio = $doctrine->getRepository(Cliente::class);
        $clientes = $repositorio->findAll();
        return $this->render('cliente/index.html.twig', [
                    'clientes' => $clientes,
        ]);
    }

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/cliente/{id<\d+>}",name="ver_cliente")
     */
    public function ver(Cliente $cliente, Request $request, ManagerRegistry $doctrine): Response {
        //Aquí añadir las incidencias que tiene dicho cliente 
        return $this->renderForm("cliente/ver.html.twig", ["cliente" => $cliente]);
    }

    /**
     * Require IS_AUTHENTICATED_FULLY for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/cliente/borrar/{id<\d+>}", name="borrar_cliente")
     */
    public function borrar(Cliente $cliente, ManagerRegistry $doctrine): Response {
        $em = $doctrine->getManager();
        $em->remove($cliente);
        $em->flush();

        $this->addFlash("aviso", "Cliente borrado");
        return $this->redirectToRoute("listado_clientes");
    }

    /**
     *  Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/cliente/insertar", name="insertar_cliente")
     */
    public function insertar(Request $request, EntityManagerInterface $entityManager): Response {

        $cliente = new Cliente();
        $form = $this->createForm(CrearClienteFormType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($cliente);
            $entityManager->flush();

            return $this->redirectToRoute('listado_clientes');
        }

        return $this->render('cliente/insertar.html.twig', [
                    'crearclienteForm' => $form->createView(),
        ]);
    }

}
