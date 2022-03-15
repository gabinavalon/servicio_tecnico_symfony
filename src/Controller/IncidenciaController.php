<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Incidencia;
use \Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CrearIncidenciaFormType;
use App\Entity\Usuario;
use App\Entity\Cliente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class IncidenciaController extends AbstractController {

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/incidencia",name="listado_incidencias")
     */
    public function index(ManagerRegistry $doctrine): Response {

        $repositorio = $doctrine->getRepository(Incidencia::class);
        $incidencias = $repositorio->findBy(array(), array('fecha_creacion' => 'ASC'));

        return $this->render('incidencia/index.html.twig', [
                    'incidencias' => $incidencias,
        ]);
    }

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/incidencia/{id<\d+>}",name="ver_incidencia")
     */
    public function ver(Incidencia $incidencia, Request $request, ManagerRegistry $doctrine): Response {
        return $this->renderForm("incidencia/ver.html.twig", ["incidencia" => $incidencia]);
    }

    /**
     * Require IS_AUTHENTICATED_FULLY for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/incidencia/borrar/{id<\d+>}", name="borrar_incidencia")
     */
    public function borrar(Incidencia $incidencia, ManagerRegistry $doctrine): Response {
        $em = $doctrine->getManager();
        $em->remove($incidencia);
        $em->flush();

        $this->addFlash("aviso", "Incidencia borrada");
        return $this->redirectToRoute("ver_cliente", ["id" => $incidencia->getCliente()->getId()]);
    }

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/incidencia/insertar/{id<\d+>}", name="insertar_incidencia_cliente")
     */
    public function insertar_desde_cliente(Cliente $cliente, Request $request, EntityManagerInterface $entityManager): Response {

        $incidencia = new Incidencia();
        $form = $this->createForm(CrearIncidenciaFormType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($incidencia);
            $entityManager->flush();
            $this->addFlash("aviso", "Incidencia añadida");
            return $this->renderForm("cliente/ver.html.twig", ["cliente" => $cliente]);
        }

        return $this->render('incidencia/insertar.html.twig', [
                    'crearincidenciaForm' => $form->createView(),
                    'cliente' => $cliente
        ]);
    }

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/incidencia/insertar", name="insertar_incidencia")
     */
    public function insertar(Request $request, EntityManagerInterface $entityManager): Response {

        $incidencia = new Incidencia();
        $form = $this->createForm(CrearIncidenciaFormType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($incidencia);
            $entityManager->flush();
            $this->addFlash("aviso", "Incidencia añadida");
            return $this->redirectToRoute('listado_incidencias');
        }

        return $this->render('incidencia/insertar2.html.twig', [
                    'crearincidenciaForm' => $form->createView()
        ]);
    }

    /**
     * Require IS_AUTHENTICATED_REMEMBERED for all the actions of this controller
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/incidencia/editar/{id<\d+>}", name="editar_incidencia")
     */
    public function editar(Incidencia $incidencia, Request $request, ManagerRegistry $doctrine): Response {

        $form = $this->createForm(CrearIncidenciaFormType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();

            $em->flush();

            $this->addFlash('aviso', 'Incidencia editada');

            return $this->renderForm("incidencia/ver.html.twig", ["incidencia" => $incidencia]);
        } else {
            return $this->render("incidencia/editar.html.twig", ["incidencia" => $incidencia, 'crearincidenciaForm' => $form->createView(),]);
        }
    }

}
