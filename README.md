# Search Bundle #

Search bundle for Symfony2. Use entities, collections for search directly.

## Simple example ##

		$product = new Entity\Product();
        $form = $this->createForm(new Form\ProductSearchType(), $product);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $form->bindRequest($request);
        }
        
        $factory = new FilterFactory($this->getDoctrine()->getEntityManager());

		/* HERE IS THE IMPORTANT PART */
        $qb = $factory->create($form->getData(), 'p')->createQueryBuilder('Padam87BaseBundle:Product');
        
        $products = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();

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


