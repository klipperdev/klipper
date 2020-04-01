Klipper Mailer Component
========================

The Mailer component is a manager to render and send an mail template with different
transporter (email, sms, etc...).

Features include:

- Available transporters:
  - Email with [Symfony Mailer](https://symfony.com/doc/current/mailer.html)
  - SMS with [Klipper SMS Sender](https://github.com/klipperdev/sms-sender)
- Twig loaders to retrieve automatically the localized templates with a fallback behavior:
  - Filesystem
  - Doctrine (optional)
- Secure the rendering for the message templates of users by activating simply the Twig Sandbox
  (only available tags, functions, etc. can be used, and templates loaded only from Doctrine)
- Disable automatically the Twig option `strict variables` for the messages rendering
- Build your custom transporters and messages with [Symfony Mime](https://symfony.com/doc/current/components/mime.html)
- Creation of template layout using the `embed` Twig tag in template message
- Direct use of transporters keeping the functionality of this component
- Template Message repository is compatible with the [Doctrine Extensions Translatable](https://github.com/Atlantic18/DoctrineExtensions)


Resources
---------

- [Documentation](https://doc.klipper.dev/components/mailer)
- [Report issues](https://github.com/klipperdev/klipper/issues)
  and [send Pull Requests](https://github.com/klipperdev/klipper/pulls)
  in the [main Klipper repository](https://github.com/klipperdev/klipper)
