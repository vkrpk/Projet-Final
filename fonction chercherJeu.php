 /**
 * @Route("/", name="home_chercher")
 */
 public function chercher(JeuRepository $jeuRepository, Request $request, FormFactoryInterface $formFactory): Response
 {
 $chercher = new Chercher();

 $form = $formFactory->create(ChercherType::class, $chercher);

 $form->handleRequest($request);

 $jeu = $jeuRepository->chercherJeu($chercher);
 // dd($jeu);
 return $this->render('home.html.twig', [
 'jeux' => $jeu,
 'form' => $form->createView()
 ]);
 }