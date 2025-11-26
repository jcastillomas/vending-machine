# Getting started

1. Run install script, you are ready to start!
    ```
    make install
    ```
## Local test environment

There are three main test batteries, you can run them all at once with:
```
make test-all
```

or you can run each of them separately

### Unit test
For domain and application layer services, you can run them with:
```
make test-unit
```

### Integration test
For infrastructure layer services, you can run them with:
```
make test-integration
```

## API documentation

You can find an openapi doc [here](../openapi/api-doc.yaml).

