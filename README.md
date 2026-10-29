# Calendário de datas fixas e móveis

Biblioteca PHP 8.2+ derivada de um exercício de 2019. Calcula Páscoa pelo calendário gregoriano, Carnaval, Sexta-feira Santa, Corpus Christi e datas fixas de referência. Mães e Pais ficam em `datasComemorativas`, separados de `feriados`.

```php
require 'CalendarioExato.php';
$calendar = new Calcular(2026);
echo $calendar->feriados['05/04']; // Páscoa
echo $calendar->SomarDias('28/02/2024', 1); // 29/02/2024
echo $calendar->dataFestiva(new DateTimeImmutable('2026-04-05'));
```

`dataFestiva()` aceita uma data para testes determinísticos ou usa o dia atual. O ano precisa corresponder ao calendário; formato inválido lança `InvalidArgumentException`. Não há impressão ou execução de exemplo ao importar o arquivo. A aritmética usa dias civis em UTC, sem somar blocos de 86.400 segundos; não depende de `easter_date` nem do limite de timestamps de 2038. Anos suportados: 1583–4099.

```sh
docker run --rm -v "$PWD:/app" -w /app php:8.4-cli php tests/run.php
```

Com PHP instalado: `php tests/run.php`. A lista é um calendário de referência, não uma classificação universal de feriados: Carnaval e Corpus Christi, em particular, dependem da localidade/organização. Datas comemorativas não implicam folga. Para aplicações reais, forneça a política local separadamente.
