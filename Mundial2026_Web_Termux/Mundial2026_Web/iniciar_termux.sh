#!/data/data/com.termux/files/usr/bin/bash
set -e
# Ejecutar desde la carpeta del proyecto, incluso si está en almacenamiento compartido.
SRC="$(cd "$(dirname "$0")" && pwd)"
if [ ! -f "$SRC/index.php" ]; then
  echo "ERROR: no encuentro index.php en: $SRC"
  exit 1
fi
DEST="$HOME/Mundial2026_Web"
mkdir -p "$DEST"
# Copia al almacenamiento privado de Termux para evitar Permission denied al crear locks.
cp -R "$SRC"/. "$DEST"/
cd "$DEST"
if ! command -v php >/dev/null 2>&1; then
  echo "Falta PHP. Instálalo con: pkg install php"
  exit 1
fi
PORT="${1:-8080}"
echo "Proyecto copiado a: $DEST"
echo "Abre Chrome en: http://127.0.0.1:$PORT"
echo "Deja Termux abierto. Para detener el servidor pulsa Ctrl+C."
exec php -S "127.0.0.1:$PORT" -t "$DEST"
