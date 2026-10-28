#!/usr/bin/env bash
# Kirim commit ke dua remote sekaligus:
#   origin = private (backup utama)   public = mirror publik
set -euo pipefail
cd "$(dirname "$0")/.."
branch="${1:-main}"
git push origin "$branch"
git push public "$branch"
echo "OK: origin + public = $(git rev-parse --short "$branch")"
