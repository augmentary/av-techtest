## Setup
The app runs using Laravel sail, so should just require a functioning docker install. Tested and working on a recent
openSUSE Tumbleweed install.

Initial installation:
```
./bin/init.sh
```

Running the dev server:
```
./vendor/bin/sail up
```

Seeding the database:
```
./vendor/bin/sail artisan migrate --seed
```

- API is available at http://localhost/api/v1/quotes
- Basic Swagger UI at http://localhost/swagger/

## Authorization
Authorization is via Bearer auth token. For convenience (and solely for the purposes of this test), the following
command will output one of the seeded tokens:
```
./vendor/bin/sail artisan app:get-token
```

## Tests
Feature and unit tests can be run via:
```
./vendor/bin/sail test
```

## Assumptions
I've taken the following reading of the requirements - apologies for any inaccuracies:
- A get endpoint which retrieves 5 random quotes, but caches them so subsequent requests retrieve the same five quotes
- A post endpoint which clears the cache and returns a new five quotes (for simplicity I've made these the same
endpoint, just different HTTP methods)
- Authentication via bearer tokens stored in the DB unhashed for simplicity (in practice hashing, encrypting or using 
JWTs might make more sense)
- Using DB caching driver for simplicity - in practice other drivers may be more suitable
- "Making third-party API response quick by cache" - with this endpoint setup I couldn't see an obvious way to fit this
in: cache clears are requested explicitly, so the only time we go to the API we *need* a fresh uncached response - it
wouldn't make sense to cache the API responses for e.g. 5 minutes. Closest I could think was to store the current
5 quotes in the cache rather than storing them in an eloquent model or similar - semantically that feels more like it's
a behavioural requirement of the API design than caching to make the API response faster, but in practice (from an API
consumer perspective) they might be the same thing.
- "Implementation of API using Laravel Manager Design Pattern" - wasn't wholly clear on expectations here.
PendingRequestManager uses the Laravel Manager pattern to define and configure the quotes API (so if there were other
HTTP clients required you could configure them here), but the implementation is very basic and mostly just replicates
Laravel's Http Macros functionality, so I'm not sure if you had something more complex in mind.  
- Some of the niceties I would usually hope to add to an API have been left out for simplicity/time - e.g. Makefile with
common commands (linting, seeding etc), auto-generation of at least some of the OpenAPI spec
- Code has been linted with PHPStan and pint.
- MySQL purely because it was the default with Sail
