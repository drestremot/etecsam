@extends('layouts.app')

@section('content')

<x-page-header variant="solid" title="Política de Privacidade" subtitle="EtecSam Reserva Labs — aplicativo e sistema web de reserva de laboratórios.">
    <x-slot:icon>
        <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
    </x-slot:icon>
</x-page-header>

<style>
    .privacy-content p     { margin-bottom: 1rem; line-height: 1.75; }
    .privacy-content h2    { font-size: 1.375rem; font-weight: 700; margin-top: 2.5rem; margin-bottom: 1rem; padding-left: 1rem; border-left: 4px solid #F5A623; }
    .privacy-content ul    { margin-bottom: 1.25rem; padding-left: 1.25rem; list-style: disc; }
    .privacy-content li    { margin-bottom: 0.5rem; line-height: 1.65; }
    .privacy-content a     { text-decoration: underline; }
</style>

<div class="container mx-auto px-4 py-16 max-w-3xl">
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">Última atualização: {{ \Illuminate\Support\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}</p>

    <div class="privacy-content text-gray-600 dark:text-gray-300">
        <div class="[&_h2]:text-etec-dark dark:[&_h2]:text-white [&_a]:text-etec-main dark:[&_a]:text-etec-accent">

        <p>
            Esta Política de Privacidade descreve como o <strong>EtecSam Reserva Labs</strong>
            (aplicativo móvel e sistema web de reserva de laboratórios da Etec Sebastiana
            Augusta de Moraes — Centro Paula Souza, unidade de Andradina/SP) coleta, usa e
            protege os dados pessoais de seus usuários — professores, auxiliares docentes e
            coordenadores da unidade escolar.
        </p>
        <p>
            O uso do aplicativo é restrito a colaboradores da unidade escolar, mediante conta
            cadastrada pela administração. Ao acessar o sistema, você concorda com os termos
            descritos abaixo.
        </p>

        <h2>1. Dados que coletamos</h2>
        <ul>
            <li><strong>Dados de identificação:</strong> nome completo, e-mail institucional e cargo/função (Professor, Auxiliar Docente ou Coordenador).</li>
            <li><strong>Foto de perfil:</strong> fotografia cadastrada no sistema para identificação visual do usuário nas telas do aplicativo.</li>
            <li><strong>Dados de reservas:</strong> laboratórios/espaços reservados, datas e horários, turmas, materiais solicitados, observações de aula, checklists de entrega e devolução de materiais e documentos digitalizados anexados às reservas.</li>
            <li><strong>Token de notificação (push):</strong> identificador técnico gerado pelo Firebase Cloud Messaging (Google) no seu dispositivo, usado exclusivamente para o envio de notificações sobre o andamento das reservas.</li>
            <li><strong>Dados técnicos de acesso:</strong> endereço IP e registros de autenticação, usados apenas para segurança e prevenção de acessos indevidos.</li>
        </ul>

        <h2>2. Como usamos os dados</h2>
        <p>Os dados coletados são utilizados exclusivamente para:</p>
        <ul>
            <li>Operar o fluxo de reserva de laboratórios (solicitação, aprovação, preparação de materiais, realização da aula e validação);</li>
            <li>Enviar notificações (push e e-mail) aos participantes de uma reserva a cada etapa concluída;</li>
            <li>Identificar visualmente os usuários dentro do sistema (foto de perfil e nome);</li>
            <li>Gerar relatórios e checklists internos de uso dos laboratórios da unidade escolar;</li>
            <li>Garantir a segurança e a autenticidade dos acessos ao sistema.</li>
        </ul>
        <p>Não utilizamos os dados para publicidade, e não os vendemos ou cedemos a terceiros para fins comerciais.</p>

        <h2>3. Compartilhamento com terceiros</h2>
        <p>
            Os dados são compartilhados apenas com prestadores de serviço estritamente
            necessários ao funcionamento técnico do aplicativo:
        </p>
        <ul>
            <li><strong>Google Firebase (Firebase Cloud Messaging):</strong> usado para o envio de notificações push. O Google processa o token do dispositivo conforme sua própria <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">política de privacidade</a>.</li>
            <li><strong>Serviço de e-mail institucional:</strong> usado para o envio de notificações por e-mail sobre o andamento das reservas entre professores, auxiliares e coordenadores.</li>
        </ul>
        <p>Nenhum dado é compartilhado com anunciantes ou corretores de dados.</p>

        <h2>4. Armazenamento e retenção</h2>
        <p>
            Os dados são armazenados em servidores contratados pela escola e mantidos pelo
            tempo necessário ao funcionamento do sistema e ao cumprimento de obrigações legais
            e de gestão escolar. Registros de reservas são mantidos como histórico institucional
            das atividades realizadas nos laboratórios.
        </p>

        <h2>5. Seus direitos (LGPD)</h2>
        <p>
            Nos termos da Lei Geral de Proteção de Dados (Lei nº 13.709/2018), você pode, a
            qualquer momento, solicitar:
        </p>
        <ul>
            <li>Confirmação da existência de tratamento de dados e acesso aos seus dados;</li>
            <li>Correção de dados incompletos, inexatos ou desatualizados;</li>
            <li>Informações sobre o compartilhamento de seus dados;</li>
            <li>Exclusão de dados, exceto quando sua manutenção for exigida por obrigação legal ou interesse institucional legítimo (ex.: histórico de uso de laboratórios).</li>
        </ul>

        <h2>6. Segurança</h2>
        <p>
            O acesso ao sistema é protegido por autenticação individual (usuário e senha).
            Adotamos medidas técnicas razoáveis para proteger os dados contra acesso não
            autorizado, perda ou alteração indevida.
        </p>

        <h2>7. Contato</h2>
        <p>
            Dúvidas sobre esta política ou solicitações relacionadas aos seus dados podem ser
            enviadas para:
        </p>
        <ul>
            <li>E-mail: <a href="mailto:e028dir@cps.sp.gov.br">e028dir@cps.sp.gov.br</a></li>
            <li>Endereço: Estrada Vicinal Sebastião Lourenço da Silva, Km 11, Andradina/SP — CEP 16900-530</li>
        </ul>

        <h2>8. Alterações desta política</h2>
        <p>
            Esta política pode ser atualizada periodicamente para refletir melhorias no
            aplicativo ou mudanças legais. A data da última atualização é exibida no topo
            desta página.
        </p>
        </div>
    </div>
</div>

@endsection
