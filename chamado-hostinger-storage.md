# Chamado para suporte Hostinger — Arquivos desaparecendo de public_html

**Domínio:** etecsam.com.br
**Caminho afetado:** `public_html/etecsam/public/storage/`

## Resumo do problema

Arquivos de imagem enviados pela aplicação (PHP/Laravel) para a pasta `public_html/etecsam/public/storage/` estão **desaparecendo do disco poucos minutos depois de serem gravados**, sem nenhuma ação nossa (sem deploy alterando código, sem comando de limpeza, sem `git` tocando nessa pasta — ela nem é rastreada pelo git). O arquivo passa a retornar HTTP 404 mesmo continuando referenciado normalmente pela aplicação.

## Teste reproduzível mais recente (27/06/2026)

1. Enviamos 3 arquivos pelo painel administrativo da aplicação:
   - `public_html/etecsam/public/storage/units/onnJeQAPt9nQgdSDwSpJNynAKbLXHj5qFkJjLBId.jpg`
   - `public_html/etecsam/public/storage/teachers/ScoC5bCpCtLNqebwshv6mMlROcEh4iGvQtQR2vKn.jpg`
   - `public_html/etecsam/public/storage/teachers/7lcnhqNLul2k7Ml7fjMh7pWfZkkIHGkoTtiX8zga.png`
2. Confirmamos via `curl` (sem cache, requisição direta ao servidor) que as 3 URLs retornavam **HTTP 200**.
3. Minutos depois, ao testar novamente (também via `curl`, sem cache de navegador/CDN, em múltiplos User-Agents), **as 3 retornaram HTTP 404** — o corpo da resposta é a página "Not Found" da própria aplicação Laravel, confirmando que o arquivo físico não existe mais em disco (a aplicação não tem nenhuma rotina que apague esses arquivos).

## Histórico

Esse mesmo padrão já se repetiu diversas vezes desde 18/06/2026. Em uma ocorrência registrada em 26/06/2026 por volta das 14h41, o número total de arquivos em `public/storage` caiu de 69 para apenas 2 arquivos, sem nenhuma ação de deploy ou administração de nossa parte que explique essa queda.

## O que já descartamos da nossa parte

- **Cache de navegador/CDN:** testado via `curl` direto ao domínio, sem headers de cache, com e sem `Referer`, em múltiplos User-Agents (desktop e mobile) — sempre 404 quando o arquivo já desapareceu.
- **Bug no nosso script de deploy:** o deploy roda via SSH e nunca executa nenhum comando de limpeza nessa pasta; revisamos linha a linha o script (`git log`, hooks, crontab do nosso usuário) e não há nenhuma rotina nossa capaz de remover esses arquivos.
- **Symlink:** a pasta é um diretório real (não symlink), confirmamos isso explicitamente.
- **Permissões/escrita:** os arquivos são gravados com sucesso (confirmado 200 logo após o upload); o problema é que desaparecem depois, não que falham ao gravar.

## O que pedimos

Poderiam verificar, do lado da infraestrutura (hPanel/disco do plano), se há algum processo de limpeza automática, rotação de cache, restart de container, ou qualquer outra rotina agendada que possa estar removendo arquivos do diretório `public_html/etecsam/public/storage/` nesta conta? Qualquer log de eventos do sistema de arquivos para esse caminho, no horário do teste acima (27/06/2026), ajudaria muito a identificar a causa.

Ficamos à disposição para mais informações ou para repetir o teste em horário combinado, se for útil para a investigação de vocês.
