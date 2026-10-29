<?php
declare(strict_types=1);
require __DIR__.'/../CalendarioExato.php';
function same(mixed $actual, mixed $expected): void { if ($actual !== $expected) { throw new RuntimeException(var_export([$actual,$expected],true)); } }
$calendar = new Calcular(2026);
same($calendar->feriados['05/04'], 'Páscoa'); same($calendar->feriados['17/02'], 'Carnaval');
same($calendar->feriados['04/06'], 'Corpus Christi'); same($calendar->datasComemorativas['10/05'], 'Dia das Mães');
same($calendar->SomarDias('28/02/2024',1), '29/02/2024');
same($calendar->SubtrairDias('2025-01-01',1,'en'), '2024-12-31');
same($calendar->dataFestiva(new DateTimeImmutable('2026-04-05')), 'Páscoa');
try { $calendar->SomarDias('31/02/2026',1); throw new RuntimeException('Invalid date accepted'); } catch (InvalidArgumentException) {}
date_default_timezone_set('America/New_York'); same($calendar->SomarDias('08/03/2026',1), '09/03/2026');
echo "Calendar, leap year, year boundary, validation and timezone tests passed.\n";
