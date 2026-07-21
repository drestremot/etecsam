# Guia de publicação — EtecSam Reserva Labs no Google Play Console

Tudo que está pronto do meu lado está listado no fim. Os passos abaixo só podem ser
feitos por você, logado na sua conta de desenvolvedor do Play Console
(https://play.google.com/console).

## 1. Criar o app

1. **Todos os apps → Criar app**
2. Nome do app: `EtecSam Reserva Labs`
3. Idioma padrão: `Português (Brasil)`
4. Tipo: **App**
5. Gratuito ou pago: **Gratuito**
6. Aceite as declarações (diretrizes do desenvolvedor e leis de exportação dos EUA) e clique em **Criar app**.

## 2. Ficha da loja (Store listing)

Vá em **Crescimento → Presença na loja → Ficha principal da loja**. Copie o conteúdo
pronto de `mobile/store_assets/store_listing_pt_BR.md`:

- **Descrição breve** (até 80 caracteres) — já pronta no arquivo.
- **Descrição completa** (até 4000 caracteres) — já pronta no arquivo.
- **Ícone do app** (512×512, PNG): use `mobile/assets/logo/icon.png` (ou gere um 512×512
  a partir dele — o Play aceita 512×512 exato).
- **Gráfico de destaque** (1024×500): `mobile/store_assets/feature_graphic_1024x500.png`.
- **Capturas de tela do telefone** (mínimo 2, recomendado 4+): use as 5 imagens que você
  já enviou. Salve-as em `mobile/store_assets/screenshots/` com esses nomes sugeridos
  (não precisa ser exatamente isso, é só organização) e faça o upload direto no Play
  Console — o upload em si só pode ser feito por você pela interface.
- **Categoria do app**: Produtividade
- **E-mail de contato**: e028dir@cps.sp.gov.br

## 3. Política de Privacidade

Em **Política do app → Política de privacidade**, informe a URL:

```
https://etecsam.com.br/politica-de-privacidade
```

Essa página já está escrita e pronta (`resources/views/pages/privacy-policy.blade.php`),
mas **só fica acessível publicamente depois que eu fizer o commit e você confirmar o
deploy** — combine comigo esse passo antes de preencher este campo, senão o Play vai
recusar a URL por não conseguir acessá-la.

## 4. Formulário de Segurança de Dados (Data safety)

Em **Política do app → Segurança dos dados**, preencha assim:

**Seu app coleta ou compartilha algum dos tipos de dados exigidos?** → Sim

| Categoria | Coletado? | Compartilhado com terceiros? | Finalidade |
|---|---|---|---|
| Nome | Sim | Não | Funcionalidade do app |
| E-mail | Sim | Não | Funcionalidade do app, comunicação |
| Fotos | Sim (foto de perfil) | Não | Personalização |
| ID do dispositivo ou outros identificadores | Sim (token de notificação push) | Sim, com o Google/Firebase (só para entregar a notificação) | Funcionalidade do app |

- **Os dados são criptografados em trânsito?** → Sim (HTTPS/TLS)
- **O usuário pode solicitar a exclusão dos dados?** → Sim, por e-mail
  (e028dir@cps.sp.gov.br)
- **A coleta de dados é obrigatória ou opcional?** → Obrigatória (o app é de uso
  interno da escola, exige login)

## 5. Classificação de conteúdo (Content rating)

Em **Política do app → Classificação de conteúdo**:

1. Categoria: **Utilitário, Produtividade, Comunicação ou similar** (não é jogo)
2. Responda **"Não"** para todas as perguntas de violência, conteúdo sexual, drogas,
   jogos de azar, linguagem imprópria, etc. — o app não tem nenhum desse conteúdo.
3. O sistema deve gerar automaticamente a classificação **Livre / Everyone**.

## 6. Público-alvo e conteúdo

Em **Política do app → Público-alvo e conteúdo**:

- Faixa etária: **18 anos ou mais** (ou "não voltado especificamente para crianças") —
  é um app de uso profissional/escolar para professores e coordenadores, não para
  alunos.
- Responda "Não" para perguntas sobre apelo a crianças.

## 7. Upload do App Bundle (AAB)

Em **Produção** (ou comece em **Teste interno/fechado**, se preferir testar antes de
publicar):

1. **Criar nova versão**
2. Faça upload de:
   ```
   mobile/build/app/outputs/bundle/release/app-release.aab
   ```
3. Nome da versão: sugestão `1.0.0 (1)` — segue o `versionName`/`versionCode` do
   `pubspec.yaml`.
4. Notas da versão (pt-BR): `Primeira versão pública do EtecSam Reserva Labs.`
5. Salvar e revisar.

**Importante sobre a assinatura**: o app já está assinado com uma chave de upload
própria (`mobile/android/app/upload-keystore.jks`). Na primeira vez que você enviar o
AAB, o Play Console vai pedir para confirmar o uso do **Play App Signing** — aceite a
opção padrão (recomendada pelo Google), que deixa o Google gerenciar a chave de
assinatura final e usa sua chave de upload só para autenticar os envios.

> ⚠️ Guarde `upload-keystore.jks` e `key.properties` em um lugar seguro (ex.: gerenciador
> de senhas ou HD externo), fora do repositório Git. Se perder essa chave, você não
> consegue mais enviar atualizações do app sem abrir um processo de recuperação com o
> Google.

## 8. Preços e distribuição

Em **Presença na loja → Países/regiões**: selecione **Brasil** (ou os países que fizerem
sentido — para um app interno de uma escola, Brasil já basta).

## 9. Enviar para revisão

Depois de preencher todas as seções acima (a barra de progresso do Play Console mostra
o que falta), vá em **Produção → Enviar para revisão**. O Google normalmente leva de
algumas horas a poucos dias para revisar apps novos.

---

## O que já está pronto (feito por mim)

- ✅ App Bundle assinado: `mobile/build/app/outputs/bundle/release/app-release.aab`
- ✅ Ícone do app (adaptativo, Android): já embutido no build via `flutter_launcher_icons`
- ✅ Gráfico de destaque: `mobile/store_assets/feature_graphic_1024x500.png`
- ✅ Descrição curta e completa: `mobile/store_assets/store_listing_pt_BR.md`
- ✅ Política de Privacidade escrita: `resources/views/pages/privacy-policy.blade.php`
  (falta só o deploy — combine comigo quando quiser publicar)
- ⏳ Screenshots: você já enviou 5 pelo chat: precisa salvá-las localmente e subir
  direto no Play Console (upload de imagem no formulário só pode ser feito por você)
