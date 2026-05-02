# SASN Tecnologia - Site Institucional

Aplicacao web institucional desenvolvida com React + Vite para apresentar servicos, solucoes e canais de contato da SASN Tecnologia.

## Analise geral do projeto

- Tipo de projeto: front-end SPA (Single Page Application) com rotas no cliente.
- Objetivo: landing page principal com secoes institucionais e paginas dedicadas para solucoes e servicos.
- Stack principal: React 18, TypeScript, Vite, Tailwind CSS, shadcn/ui e React Router.
- UX/UI: interface moderna com componentes reutilizaveis e foco em navegacao simples para conversao (contato e orcamento).
- Integracoes atuais: links diretos para WhatsApp, e-mail e mapa; formulario de contato local (sem envio para backend).

## Tecnologias e bibliotecas

- React + TypeScript
- Vite
- Tailwind CSS + tailwindcss-animate
- shadcn/ui + Radix UI
- React Router DOM
- TanStack React Query (infra disponivel)
- React Helmet Async
- Lucide Icons

## Estrutura principal

```txt
src/
  components/          # secoes da pagina e componentes reutilizaveis
  components/ui/       # biblioteca de componentes de interface (shadcn/ui)
  pages/               # paginas e rotas principais
  hooks/               # hooks customizados
  lib/                 # utilitarios
```

## Rotas atuais

- `/` - pagina inicial
- `/solucoes/websites`
- `/solucoes/sistemas`
- `/solucoes/servidores`
- `/solucoes/seguranca`
- `/solucoes/banco-dados`
- `/solucoes/suporte-tecnico`
- `/suporte-corporativo`
- `/projetos`
- `*` - fallback para pagina 404

## Requisitos

- Node.js 18+ (recomendado)
- npm 9+

## Como executar localmente

```sh
# 1) Instalar dependencias
npm install

# 2) Criar arquivo .env a partir de .env.example
# (no Windows, copie manualmente; no Linux/Mac, pode usar: cp .env.example .env)

# 3) Subir ambiente de desenvolvimento
npm run dev
```

Servidor local padrao: `http://localhost:8080` (porta definida no `vite.config.ts`).

## Configuracao de ambiente

Defina no arquivo `.env`:

```env
VITE_CONTACT_EMAIL=contato@sasntecnologia.com.br
```

- `VITE_CONTACT_EMAIL`: e-mail de destino usado no envio do formulario via FormSubmit.
- Se o envio por e-mail falhar, o sistema usa WhatsApp automaticamente como plano B.

## Scripts disponiveis

- `npm run dev` - inicia servidor de desenvolvimento
- `npm run build` - gera build de producao
- `npm run build:dev` - gera build em modo development
- `npm run preview` - executa preview do build
- `npm run lint` - executa verificacao de lint

## Observacoes tecnicas

- O projeto possui boa separacao por componentes e paginas.
- O alias `@` aponta para `src/`, simplificando imports.
- A tipagem TypeScript esta configurada com regras mais flexiveis (`strict: false` em `tsconfig.app.json`).
- Nao ha testes automatizados configurados no momento.
- O formulario de contato mostra notificacao local (toast), sem persistencia ou API.

## Proximos passos sugeridos

- Integrar formulario com servico sem backend (ex.: WhatsApp, FormSubmit ou Formspree) com validacao e anti-spam.
- Ativar validacoes TypeScript mais rigorosas gradualmente.
- Adicionar testes (unitarios e/ou E2E) para rotas e componentes criticos.
- Definir pipeline de CI para lint + build automaticos.
