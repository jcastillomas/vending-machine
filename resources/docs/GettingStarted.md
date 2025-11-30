# Getting started

1. Run install script:
```
make install
```
## Restore factory DB 

In out local environment we added a tool to "restore from factory" the database, this tool is added in order to
fix the problematic with the test, because doing integration, and acceptance tests we will modify the local database data.

With this tool, after the acceptance and integration tests has been executed we will execute this "fix-me" tool.

Sometimes when we are testing in the local environment and some test fails, it could happen that our DB has in an inconsistent state
in this case we can run:`make fix-me` it will force to restore it.
## Local test environment

There are three main test batteries, you can run them all at once with:`make test-all`
or you can run each of them separately

### Unit test
For domain and application layer services, you can run them with:`make test-unit`
### Integration test
For infrastructure layer services, you can run them with:`make test-integration`
### Acceptance test
For end-to-end functional tests. You can run them with:`make test-acceptance`
## API documentation

You can find an openapi doc [here](../openapi/api-doc.yaml) in order to know all the endpoints, the domain models, and a sandbox to execute each request in our local environment.

