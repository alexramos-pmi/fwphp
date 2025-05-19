#!/bin/bash

echo "🔧 Iniciando build de produção..."

# Garante que as dependências estão instaladas
if [ ! -d "node_modules" ]; then
  echo "📦 Instalando dependências npm..."
  npm install
fi

# Executa build
echo "⚙️ Rodando build..."
npm run build

# Garante que os arquivos estão com permissões corretas
echo "🔐 Ajustando permissões de dist/"
chmod -R 755 public/dist

echo "✅ Build de produção finalizado."