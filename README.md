# Search Bundle #

Search bundle for Symfony2. Use entities, collections for search directly.

## 1. Examples ##

Check the [DefaultController](https://github.com/Padam87/SearchBundle/blob/master/Controller/DefaultController.php).

## 2. Installation ##

### 2.1. Composer ###

    "padam87/search-bundle": "dev-master",

### 2.2. AppKernel ###

    $bundles = array(
		...
        new Padam87\SearchBundle\Padam87SearchBundle(),
    );

### 2.3. Check config.yml ###

Don't forget to add the bundle, if all_bundles is set to false

	jms_di_extra:
	    locations:
	        all_bundles: false
	        bundles: [Padam87SearchBundle]

### 2.4. Routing (optional, for working examples) ###

	Padam87SearchBundle:
	    resource: "@Padam87SearchBundle/Controller/"
	    type:     annotation
	    prefix:   /

## 3. Dependencies

None.

For working examples, you will need the [Padam87BaseBundle](https://github.com/Padam87/BaseBundle), and optionally Twitter's bootsrtap for design.