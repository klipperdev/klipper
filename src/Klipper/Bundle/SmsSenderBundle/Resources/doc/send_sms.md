Send SMS
========

## Basic usage

You can use directly the SMS Sender service to send your SMS.

```php
// src/Controller/SmsController.php
namespace App\Controller;

use Klipper\Component\SmsSender\SmsSenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Klipper\Component\SmsSender\Mime\Sms;

class SmsController extends AbstractController
{
    /**
     * @Route("/sms")
     */
    public function sendSms(SmsSenderInterface $smsSender)
    {
        $sms = (new Sms())
            ->to('+33612345678')
            ->text('Sending SMS is fun again!');

        $smsSender->send($sms);

        // ...
    }
}
```
