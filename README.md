# Search Bundle #

Search bundle for Symfony2. Use entities, collections for search directly.

## Simple example ##

In this example we search the AcmePizzaBundle:Customer with the entity posted by the form.

		$Customer = new Entity\Customer();
		$form = $this->createForm(new Form\CustomerSearchType(), $Customer);
		
		$request = $this->getRequest();
		
		if('POST' === $request->getMethod()) {
		    $form->bindRequest($request);
		    
		    $Customer = $form->getData();
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$qb = $em->getRepository('AcmePizzaBundle:Customer')->createQueryBuilder('c');
		
		$factory = new FilterFactory($em);
		
		$filter = $factory->create($Customer, 'c');
		if($filter->toExpr() != false) {
		    $qb->where($filter->toExpr());
		
		    foreach($filter->toParameters() as $parameter) {
		        $qb->setParameter($parameter['token'], $parameter['value']);
		    }
		}
		
		$customers = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();

## More examples

More examples can be found in the **DefaultCountroller**

### Routing:

		Padam87SearchBundle:
		    resource: "@Padam87SearchBundle/Controller/"
		    type:     annotation
		    prefix:   /

### AppKernel:

        $bundles = array(
			...
            new Padam87\SearchBundle\Padam87SearchBundle(),
        );

### Autoload:

		$loader->registerNamespaces(array(
		    ...
		    'Padam87'          => __DIR__.'/../vendor/bundles',
		));

## Dependencies

None. If you would like to test the examples in the DefaultController, you will need the AcmePizzaBundle, and optionally Twitter's bootsrtap for design.


