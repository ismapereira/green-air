# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato baseia-se em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/), 
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [Não Lançado]
### Adicionado
- Modal de **Termos de Uso e Política de Privacidade** na página de cadastro (validação interceptando o clique no botão "Cadastrar" para exigir aceite antes de registrar no banco).

## [1.0.0] - Nova Estrutura MVC - Marco Zero
### Adicionado
- **Arquitetura Base:** Padrão MVC customizado do zero utilizando nativamente PHP, sem frameworks pesados, garantindo altíssimo desempenho.
- **Roteador:** Motor de rotas amigáveis com front-controller.
- **Autenticação:** Sistema de login seguro protegendo contas e senhas (bcrypt).
- **Banco de Dados Relacional:** Estrutura otimizada para escalabilidade com chaves estrangeiras entre utilizadores, árvores, espécies, sistema de pontos e níveis de usuários (Bronze, Prata, Ouro).
- **Proteção Upload:** Camada protetora em `.htaccess` e indexamento validando tipo MIME em fotos de usuários e de árvores cadastradas.
- **Design Web:** Interface inteiramente nova utilizando Flexbox/CSS Grid, cores vibrantes customizadas sem bibliotecas externas (Vanilla CSS).
- **Dashboard:** Visão geral de árvores por usuário, clima integrado via OpenWeather API e sistema do mapa base.
