<?php
declare(strict_types=1);

class Calendario
{
    /** @var array<string,string> Datas de referência; a aplicabilidade varia por localidade. */
    public array $feriados;
    /** @var array<string,string> */
    public array $datasComemorativas;
    public readonly int $ano;

    public function __construct(?int $ano = null)
    {
        $this->ano = $ano ?? (int)date('Y');
        if ($this->ano < 1583 || $this->ano > 4099) {
            throw new InvalidArgumentException('Year must be between 1583 and 4099.');
        }
        $this->feriados = ['01/01'=>'Ano Novo', '21/04'=>'Tiradentes', '01/05'=>'Dia do Trabalho', '07/09'=>'Independência do Brasil', '12/10'=>'Nossa Senhora Aparecida', '02/11'=>'Finados', '15/11'=>'Proclamação da República', '20/11'=>'Consciência Negra', '25/12'=>'Natal'];
        $easter = $this->pascoa($this->ano);
        foreach ([0=>'Páscoa', -47=>'Carnaval', -2=>'Sexta-feira Santa', 60=>'Corpus Christi'] as $days=>$name) {
            $this->feriados[$easter->modify(sprintf('%+d days', $days))->format('d/m')] = $name;
        }
        $this->datasComemorativas = [
            (new DateTimeImmutable("second sunday of May {$this->ano}", new DateTimeZone('UTC')))->format('d/m')=>'Dia das Mães',
            (new DateTimeImmutable("second sunday of August {$this->ano}", new DateTimeZone('UTC')))->format('d/m')=>'Dia dos Pais',
        ];
    }

    private function pascoa(int $year): DateTimeImmutable
    {
        // Gregorian computus, independent of the calendar extension and Unix timestamp limits.
        $a=$year%19; $b=intdiv($year,100); $c=$year%100; $d=intdiv($b,4); $e=$b%4;
        $f=intdiv($b+8,25); $g=intdiv($b-$f+1,3); $h=(19*$a+$b-$d-$g+15)%30;
        $i=intdiv($c,4); $k=$c%4; $l=(32+2*$e+2*$i-$h-$k)%7; $m=intdiv($a+11*$h+22*$l,451);
        $month=intdiv($h+$l-7*$m+114,31); $day=($h+$l-7*$m+114)%31+1;
        return new DateTimeImmutable(sprintf('%04d-%02d-%02d',$year,$month,$day), new DateTimeZone('UTC'));
    }

    public function SomarDias(string $data, int $n_dias, string $forma = 'pt'): string
    {
        $format = str_contains($data, '/') ? 'd/m/Y' : 'Y-m-d';
        $date = DateTimeImmutable::createFromFormat('!'.$format, $data, new DateTimeZone('UTC'));
        if (!$date || $date->format($format) !== $data) { throw new InvalidArgumentException('Invalid date.'); }
        if (!in_array($forma, ['pt','en'], true)) { throw new InvalidArgumentException('Format must be pt or en.'); }
        return $date->modify(sprintf('%+d days',$n_dias))->format($forma === 'pt' ? 'd/m/Y' : 'Y-m-d');
    }

    public function SubtrairDias(string $data, int $n_dias, string $forma = 'pt'): string
    { return $this->SomarDias($data, -$n_dias, $forma); }
}

class Calcular extends Calendario
{
    public function dataFestiva(?DateTimeInterface $data = null): ?string
    {
        $data ??= new DateTimeImmutable('now');
        if ((int)$data->format('Y') !== $this->ano) { throw new InvalidArgumentException('Date must belong to the calendar year.'); }
        return $this->feriados[$data->format('d/m')] ?? null;
    }
}
