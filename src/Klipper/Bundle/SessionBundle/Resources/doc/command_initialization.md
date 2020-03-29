Command Initialization
======================

## Usage

Execute command:

``` bash
$ php app/console init:session:pdo
```

## Configure the session handler

Edit the `app/config/config.yml`:

```yaml
#...

framework:
    session:
        handler_id: klipper_session.handler.pdo
```
