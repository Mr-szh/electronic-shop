<?php

return [
    'alipay' => [
        'app_id' => '2016101100660828',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAh6RdkkyIAHKLBWy1u4zJ7/3KUrWXOs9ZS2xKtobs9TCU2kokSDYOOkQ8Sw2WCBa2LGLlzJDGBVDTf2C92YK9YBx8G4X0mpu75NMN2MvowshuAniLo1XsyB6/VpXIzhCYhKWj0p9KNSZpgeHBxJIP2W3Nanos4jjH19ckYcnmtjCNLuV/25nzSO7ZrmSuTLaEaCGBXMs5F0wchMStDgIeY/u0eIkYo1T0Ll2A18VsXCP/DD0MrubBRPWnrSdXEg1SCDnB88IyeD+zwtULgH64UTntT0OU7Y3kdL5rcvn9eFHc9iwUSfDpjO6K/j54ch9+cSSyWLhgFghhcAr0H01ACwIDAQAB',
        'private_key' => 'MIIEpAIBAAKCAQEAqSMSOn5KY8Wi7Ogg2Jr7IiHkjLG06BqxEbLkV605JRFPnggi1u8gEVWFjmW6/UnUvV6klAyKf9rzeM312ZT7Uq+Gb2SJvJ+IechslCCsR2qp2THFbMJZ1ZABXa7xS6WQLQZtLHoPXbVJY8uRalF5cjFIAFU7CfAgVCIYB3yI7CGW2PfOOEcF+55Zwmsl0pwlOJamchHUjGK1GuVzaRG/IcA2/lF0kLskuarEefY+zW5BZluVad0OonSxnbhor1FrsnKZuGdKrZH57NOx6r3JFglzBbinTEODCNA+DEdvfVK+agXbA05Sh0xvVb7WL4sjb2C4bgyv0zBrQ03Kdg9c8wIDAQABAoIBABSpaiKnJuNFU3/pY4nSdPdYeJkPZHAuQo9M8UQcryxuDvHZQJhNIfe8INKibtNeX5S6qRRE6+5uiuupDW2FG6agNqYI/CKEwqFN8KWv0BvxVcpooBuHKn5CV0hghK2fDhvR+yhByPrDmLGOPrdkOJfQmWEhCFNQeEKO36yadAeUaAbCKVSOnP/Rt4QQGp/3Cx2IsAVYsXIozgJSWzA5OZIp/vXrnEBShyNqJqL57I1A4AUSoCRcnHR2D+yzLhXJcO3NPGsyXZ7/OUzxAPTfAcetHxx+yZJbpWkaoAzTC3pVc+F0+L8BdmEsUoOA7ENMcEtjgFWnva02gCXfYugd0yECgYEA0xXDqYIqoU1tbEoV3W65F7QMTnfLUiF9oUgxny0fK4VzuHiRW+ikJ77Jn3DFgbhqGZq0K24Yt7lTgOfh1j9YsgCjo3gf7mp9h15yjGYQOixQWri7FgmgsI4o8tESUFy2XNKYzJ8CHrFPRht3LO+Vi5BtXvE+mhgSwWP/wTpPjgsCgYEAzSBP1PCxWgzx9MSDHUjIKI0NZUdQlqnAbXKig4IW+M1dJlXVAM39CnnIdSG6490DOCs92rMHhueDqgDBL9s7+uLKWa7DRfaQ89ylpHeRp4M+N8cDpAaLzo3WhHxnUHRLWN1tHRn6ji4HRoX0BPMxyP3YkABqeA8D6Uz/WaMShbkCgYEA0QbiZP9TO7wyuWN713UHsWNINIQIQWKMx6N4EApcrXpPp8MtHnh35ivV+R9xVg3Kc2RxOfiiXX0WJbZ6OvEr4ckkQoJ4DF5DxENQ55tCQnkTcWMxUJdkGrIL9rf2jMHijHS3fxmOvdoTiHc2QrdVvYjd5YC731granqeDzixAxUCgYEAvW+VymXuo+dInG/PVoAcubZqJJpTxpBGcc1oYGMHrAacfAbQi8mwYrhNdD8ORdmaW4kzuoGT9fZhFeNUhknFftFrZp+LH1WyUEP5hYYXMch28TQOHLEe9EbxUbFCCH8eBpeXaUTQq/W+KlcTIG8oDyK/mV2I9g+H4TGGsNV1CaECgYBda17Pgu9hrrOnkcwzDfhLHRt61/w5Q00SxukIbEAArhEXDiUFp+KZF+LKYqfxxf5lrJ+51JrKegKtewoXuPg4MS7z+RwzBfs6sp16TrxvtHPeEsKSwRYTHTPfoel8PsDNfwnjoLI/a1ISLV39z0wYMI5yIqwN6vdC91P3Q1oVGg==',
        'log' => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id' => '',
        'mch_id' => '',
        'key' => '',
        'cert_client' => '',
        'cert_key' => '',
        'log' => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];