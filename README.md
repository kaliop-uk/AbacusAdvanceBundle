Abacus Advance Bundle
=====================

> API to connect easily to Abacus AD gateway API, version v0.9.14

This API Gateway allows to:

- call easily the Abacus ADvance API
- receive http callbacks made from ADvance in some configurations


## External docs

* https://www.abacusemedia.com/advance


## Requirements

* guzzlehttp/guzzle: ~6.0
* symfony - various components: Config, OptionsResolver, HttpFoundation, Serializer, HttpKernel, DependencyInjection


## Installation

* Install the bundle using composer
* Activate the Abacus\AdvanceBundle\AbacusAdvanceBundle in your Sf app kernel
* Set up the required parameters (see parameters.yml in this bundle)


## Using the bundle
 
### Making API calls to ADvance

To make calls to Advance you have to retrieve the `abacus.advance.api` as a service, and from it retrieve the specific
service that you need: Gatekeeper Access Allowed, Log web activity, Get products list, etc...

Each service can return one or many responses pre-formatted.

### Receiving callbacks

To be documented...

### Using the local API Stub to emulate ADvance 

To be documented...


## Examples

### Gatekeeper Access Allowed

````php
    // Get Abacus ADvance api as a service
    $advanceApiGateway = $this->get('abacus.advance.api');
    
    // Get GateKeeper service
    $gateKeeperService = $advanceApiGateway->getGateKeeper();
    
    // Call API and get response (NB: can throw an exception)
    $accessAllowResponse = $gateKeeperService
        ->accessAllowed(
            [
                'Url'      => 'myUrl',
                'CookieID' => 'myAnonymousUserId',
            ]
        );
    
    // Use response methods
    $access = $accessAllowResponse->hasAccessGranted(); // true|false
    $accessStatus = $accessAllowResponse->getAccessStatus(); // GateKeeper::ACCESS_GRANTED|GateKeeper::ACCESS_DENIED
    
    // Or get response as Abacus returned to us
    $rawResponse = $accessAllowResponse->getRawResponse();

    // See also getData, getVersion, getStatusCode, getStatusMessages functions
````


## Authors

* [Rémi Souverain](mailto:rsouverain@kaliop.com?subject=[Abacus\\AdvanceBundle])
* [Yann Roseau](mailto:yroseau@kaliop.com?subject=[Abacus\\AdvanceBundle])
* Gaetano Giunta
