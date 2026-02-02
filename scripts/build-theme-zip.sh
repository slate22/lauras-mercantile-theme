#!/usr/bin/env bash
set -euo pipefail

VERSION="${1:-dev}"
THEME_SRC="wp-content/themes/lauras-mercantile-hybrid-enhanced 3"
DIST_DIR="dist"

# Sanitize version for folder name
SAFE_VERSION="${VERSION//[^a-zA-Z0-9._-]/}"

THEME_NAME="lauras-mercantile-hybrid-enhanced-gpt-${SAFE_VERSION}"
OUT_DIR="${DIST_DIR}/${THEME_NAME}"

echo "Building theme: ${THEME_NAME}"

rm -rf "${DIST_DIR}"
mkdir -p "${OUT_DIR}"

# Copy theme source
rsync -a \
  --exclude "react-src/node_modules" \
  --exclude ".git" \
  --exclude "*.zip" \
  "${THEME_SRC}/" "${OUT_DIR}/"

# Ensure dist exists (React build output)
if [ ! -d "${OUT_DIR}/assets/dist" ]; then
  echo "ERROR: assets/dist missing after build"
  exit 1
fi

# Zip it
mkdir -p "${DIST_DIR}"
cd "${DIST_DIR}"
zip -qr "${THEME_NAME}.zip" "${THEME_NAME}"

echo "Created ${DIST_DIR}/${THEME_NAME}.zip"
