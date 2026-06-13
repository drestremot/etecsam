<?php

namespace Database\Seeders;

use App\Models\SiteTheme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            // ── MESES ────────────────────────────────────────────────────────
            [
                'name'            => 'Janeiro Branco & Roxo',
                'slug'            => 'janeiro-branco-roxo',
                'month'           => 1,
                'primary_color'   => '#6a0dad',
                'secondary_color' => '#9b30e0',
                'accent_color'    => '#ffffff',
                'text_color'      => '#ffffff',
                'description'     => 'Janeiro traz duas campanhas importantes. O Janeiro Branco busca conscientizar as pessoas sobre a saúde mental — o branco representa a folha em branco, convidando cada um a refletir sobre seus pensamentos, sentimentos e desejos para o novo ano. O Janeiro Roxo é a campanha de prevenção e tratamento à hanseníase, doença infecciosa crônica e curável que causa lesões de pele e danos aos nervos.',
            ],
            [
                'name'            => 'Fevereiro Roxo & Laranja',
                'slug'            => 'fevereiro-roxo-laranja',
                'month'           => 2,
                'primary_color'   => '#7b2d8b',
                'secondary_color' => '#e65c00',
                'accent_color'    => '#ce93d8',
                'text_color'      => '#ffffff',
                'description'     => 'O Fevereiro Roxo é uma ação de conscientização para doenças como lúpus, fibromialgia e Alzheimer, unindo esforços em torno de causas que merecem mais atenção e pesquisa. Já o Fevereiro Laranja é uma campanha de sensibilização sobre a leucemia, incentivando o diagnóstico precoce e o apoio às pessoas afetadas por esse tipo de câncer do sangue.',
            ],
            [
                'name'            => 'Março Azul',
                'slug'            => 'marco-azul',
                'month'           => 3,
                'primary_color'   => '#1565c0',
                'secondary_color' => '#0d47a1',
                'accent_color'    => '#64b5f6',
                'text_color'      => '#ffffff',
                'description'     => 'A campanha do Março Azul é voltada ao debate sobre a prevenção ao câncer colorretal, doença que recentemente vitimou o Rei Pelé. O câncer colorretal é o segundo mais incidente no Brasil, mas tem altas chances de cura quando detectado precocemente. A campanha reforça a importância do rastreamento e dos exames preventivos.',
            ],
            [
                'name'            => 'Abril Verde & Azul',
                'slug'            => 'abril-verde-azul',
                'month'           => 4,
                'primary_color'   => '#1b5e20',
                'secondary_color' => '#1565c0',
                'accent_color'    => '#69f0ae',
                'text_color'      => '#ffffff',
                'description'     => 'O Abril Verde conscientiza sobre a importância da segurança no trabalho, prevenindo acidentes e doenças ocupacionais — o Brasil é um dos países com maiores índices de acidentes de trabalho no mundo. O Abril Azul tem como objetivo promover o debate sobre o autismo (Transtorno do Espectro Autista — TEA), combatendo o preconceito e valorizando a neurodiversidade.',
            ],
            [
                'name'            => 'Maio Amarelo',
                'slug'            => 'maio-amarelo',
                'month'           => 5,
                'primary_color'   => '#f9a825',
                'secondary_color' => '#f57f17',
                'accent_color'    => '#fff176',
                'text_color'      => '#333333',
                'description'     => 'O Maio Amarelo é uma campanha de âmbito mundial que busca conscientizar a sociedade sobre a importância da prevenção de acidentes de trânsito. A cor amarela remete ao sinal de atenção e alerta. Os acidentes de trânsito matam mais de 1,35 milhão de pessoas por ano no mundo. O objetivo é engajar pessoas, entidades e governos na luta por trânsito mais seguro.',
            ],
            [
                'name'            => 'Junho Vermelho & Laranja',
                'slug'            => 'junho-vermelho-laranja',
                'month'           => 6,
                'primary_color'   => '#b71c1c',
                'secondary_color' => '#e65100',
                'accent_color'    => '#ff8a65',
                'text_color'      => '#ffffff',
                'description'     => 'O Junho Vermelho faz alusão à importância da doação de sangue — o sangue é insubstituível e cada doação pode salvar até quatro vidas. Aproximadamente 38% da população mundial pode doar sangue, mas apenas 10% o faz. Já o Junho Laranja é voltado para a conscientização sobre a anemia falciforme e a leucemia, incentivando o diagnóstico precoce e o cuidado com a saúde.',
            ],
            [
                'name'            => 'Julho Amarelo & Verde',
                'slug'            => 'julho-amarelo-verde',
                'month'           => 7,
                'primary_color'   => '#f57f17',
                'secondary_color' => '#2e7d32',
                'accent_color'    => '#ffcc02',
                'text_color'      => '#ffffff',
                'description'     => 'O Julho Amarelo destaca a conscientização sobre as hepatites virais, doenças silenciosas que afetam o fígado e que podem evoluir para cirrose e câncer. Também aborda o câncer ósseo, incentivando o diagnóstico precoce. O Julho Verde é uma campanha de sensibilização e combate ao câncer de cabeça e pescoço, que representa cerca de 10% dos tumores diagnosticados no Brasil.',
            ],
            [
                'name'            => 'Agosto Dourado',
                'slug'            => 'agosto-dourado',
                'month'           => 8,
                'primary_color'   => '#e65100',
                'secondary_color' => '#bf360c',
                'accent_color'    => '#ffd54f',
                'text_color'      => '#ffffff',
                'description'     => 'O Agosto Dourado é uma ação de informação e promoção do aleitamento materno, realizada geralmente entre os dias 1 e 7 de agosto, com a Semana Mundial da Amamentação. O ouro representa o que há de mais precioso — o leite materno é o alimento ideal para os bebês nos primeiros meses de vida, protegendo contra doenças e fortalecendo o vínculo entre mãe e filho.',
            ],
            [
                'name'            => 'Setembro Verde, Vermelho & Amarelo',
                'slug'            => 'setembro-multiplas-cores',
                'month'           => 9,
                'primary_color'   => '#1b5e20',
                'secondary_color' => '#b71c1c',
                'accent_color'    => '#f9a825',
                'text_color'      => '#ffffff',
                'description'     => 'Setembro é o mês com mais campanhas: o Setembro Verde visa sensibilizar sobre a doação de órgãos e a prevenção ao câncer de intestino; o Setembro Vermelho conscientiza sobre a prevenção de doenças cardiovasculares, principal causa de morte no mundo; e o Setembro Amarelo é a campanha mais difundida, trazendo ao debate a prevenção ao suicídio — uma causa que, no Brasil, vitima mais de 12 mil pessoas por ano.',
            ],
            [
                'name'            => 'Outubro Rosa',
                'slug'            => 'outubro-rosa',
                'month'           => 10,
                'primary_color'   => '#ad1457',
                'secondary_color' => '#c2185b',
                'accent_color'    => '#f48fb1',
                'text_color'      => '#ffffff',
                'description'     => 'Indiscutivelmente a campanha mais conhecida de todas, o Outubro Rosa foi criado nos Estados Unidos na década de 1990, sendo dedicado à conscientização sobre o câncer de mama. É o tipo de câncer mais incidente entre mulheres no Brasil. O diagnóstico precoce aumenta em até 95% as chances de cura. A campanha incentiva o autoexame e a realização de mamografias regularmente.',
            ],
            [
                'name'            => 'Novembro Azul & Dourado',
                'slug'            => 'novembro-azul-dourado',
                'month'           => 11,
                'primary_color'   => '#0d47a1',
                'secondary_color' => '#1565c0',
                'accent_color'    => '#ffd600',
                'text_color'      => '#ffffff',
                'description'     => 'O Novembro Azul é a ação de combate ao câncer de próstata e incentivo ao cuidado com a saúde masculina — o câncer de próstata é o segundo mais incidente em homens no Brasil. O Novembro Dourado conscientiza a população sobre o câncer infantojuvenil: o ouro representa a criança e o adolescente, e a campanha busca atenção ao diagnóstico precoce e ao apoio às famílias afetadas.',
            ],
            [
                'name'            => 'Dezembro Vermelho & Laranja',
                'slug'            => 'dezembro-vermelho-laranja',
                'month'           => 12,
                'primary_color'   => '#b71c1c',
                'secondary_color' => '#e65100',
                'accent_color'    => '#ff8a65',
                'text_color'      => '#ffffff',
                'description'     => 'O mês de dezembro encerra as campanhas com duas causas importantes: o Dezembro Vermelho ressalta a importância da prevenção contra o HIV/AIDS — o Brasil é um dos países com maior número de pessoas vivendo com HIV no mundo, e o tratamento gratuito está disponível pelo SUS. O Dezembro Laranja busca conscientizar sobre o combate ao câncer de pele, o tipo mais frequente no Brasil, responsável por 33% de todos os tumores registrados.',
            ],

            // ── DATAS ESPECIAIS ───────────────────────────────────────────────
            [
                'name'            => 'Natal',
                'slug'            => 'natal',
                'month'           => null,
                'primary_color'   => '#b71c1c',
                'secondary_color' => '#1b5e20',
                'accent_color'    => '#ffd700',
                'text_color'      => '#ffffff',
                'description'     => 'O Natal é celebrado em 25 de dezembro e representa para o mundo cristão o nascimento de Jesus Cristo. É também uma data de confraternização, generosidade e esperança para pessoas de todas as culturas. As cores vermelha e verde do Natal têm origens pagãs e medievais: o verde das folhas de azevinho simbolizava a vida no inverno, e o vermelho representava as bagas e o amor.',
            ],
            [
                'name'            => 'Dia dos Namorados',
                'slug'            => 'dia-dos-namorados',
                'month'           => null,
                'primary_color'   => '#880e4f',
                'secondary_color' => '#ad1457',
                'accent_color'    => '#ff80ab',
                'text_color'      => '#ffffff',
                'description'     => 'O Dia dos Namorados é celebrado no Brasil em 12 de junho, véspera de Santo Antônio, padroeiro dos namorados e casamentos. É uma data de celebração do amor e do afeto entre casais. As cores rosa e vermelho representam o amor, carinho e paixão. É uma das datas comerciais mais importantes do Brasil.',
            ],
            [
                'name'            => 'Copa do Mundo — Brasil',
                'slug'            => 'copa-do-mundo',
                'month'           => null,
                'primary_color'   => '#009c3b',
                'secondary_color' => '#002776',
                'accent_color'    => '#ffdf00',
                'text_color'      => '#ffffff',
                'description'     => 'A Copa do Mundo FIFA é o maior evento esportivo do planeta, realizado a cada quatro anos. O Brasil é o país com mais títulos mundiais — cinco conquistas (1958, 1962, 1970, 1994 e 2002). As cores da Seleção Brasileira — verde, amarelo, azul e branco — representam a bandeira nacional e são reconhecidas em todo o mundo como símbolo do futebol e da garra brasileira.',
            ],
            [
                'name'            => 'Consciência Negra',
                'slug'            => 'consciencia-negra',
                'month'           => null,
                'primary_color'   => '#1a1a1a',
                'secondary_color' => '#006400',
                'accent_color'    => '#ffd700',
                'text_color'      => '#ffffff',
                'description'     => 'O Dia da Consciência Negra é celebrado em 20 de novembro, data que marca o assassinato de Zumbi dos Palmares em 1695, símbolo da resistência negra no Brasil. É um momento de reflexão sobre a luta dos negros por igualdade, respeito e valorização de sua cultura e história. As cores preta, verde e dourada são as cores pan-africanas, símbolos da identidade e da resistência do povo negro.',
            ],
            [
                'name'            => 'Páscoa',
                'slug'            => 'pascoa',
                'month'           => null,
                'primary_color'   => '#6a1e9e',
                'secondary_color' => '#388e3c',
                'accent_color'    => '#ffd740',
                'text_color'      => '#ffffff',
                'description'     => 'A Páscoa é celebrada por cristãos como a ressurreição de Jesus Cristo e é uma das datas mais importantes do calendário cristão. As cores vibrantes — roxo (penitência e realeza), verde (vida e esperança) e amarelo/dourado (alegria e luz) — são associadas à primavera no hemisfério norte e à renovação da vida. No Brasil, é também uma tradição de confraternização em família com chocolate e ovos de Páscoa.',
            ],
            [
                'name'            => 'Festa Junina',
                'slug'            => 'festa-junina',
                'month'           => null,
                'primary_color'   => '#8b1a00',
                'secondary_color' => '#e65c00',
                'accent_color'    => '#ffcc00',
                'text_color'      => '#ffffff',
                'description'     => 'As Festas Juninas são celebradas em junho em homenagem a São João, Santo Antônio e São Pedro. São uma das manifestações culturais mais ricas e populares do Brasil, com música, dança, comidas típicas e trajes caipiras. As cores quentes — vermelho, laranja e amarelo — remetem às bandeirolas coloridas, às fogueiras e à alegria das quadrilhas. É um patrimônio cultural imaterial do povo brasileiro.',
            ],
        ];

        foreach ($themes as $data) {
            SiteTheme::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['active' => false])
            );
        }
    }
}
