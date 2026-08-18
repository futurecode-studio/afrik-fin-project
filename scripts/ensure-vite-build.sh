#!/usr/bin/env bash
set -euo pipefail

# Keep Laravel (@vite manifest) and the public docroot serving the same /build output.
CORE_DIR="${1:-.}"
DOCROOT_DIR="${2:-../public_html}"

CORE_DIR="$(cd "$CORE_DIR" && pwd)"
DOCROOT_DIR="$(cd "$DOCROOT_DIR" && pwd)"

BUILD_TARGET="$CORE_DIR/public/build"
BUILD_LINK="$DOCROOT_DIR/build"
MANIFEST="$BUILD_TARGET/manifest.json"

if [[ ! -f "$MANIFEST" ]]; then
    echo "ERROR: Vite manifest missing at $MANIFEST"
    echo "Run: npm ci && npm run build"
    exit 1
fi

verify_manifest_assets() {
    php -r '
        $manifestPath = $argv[1];
        $root = $argv[2];
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (! is_array($manifest)) {
            fwrite(STDERR, "Invalid Vite manifest\n");
            exit(1);
        }
        $files = [];
        foreach ($manifest as $entry) {
            if (! empty($entry["file"])) {
                $files[] = $entry["file"];
            }
            foreach ($entry["css"] ?? [] as $css) {
                $files[] = $css;
            }
        }
        foreach (array_unique($files) as $file) {
            $path = $root . "/" . $file;
            if (! is_file($path)) {
                fwrite(STDERR, "Missing build asset: {$file}\n");
                exit(1);
            }
        }
    ' "$MANIFEST" "$BUILD_TARGET"
}

link_build_directory() {
    if [[ -e "$BUILD_LINK" && ! -L "$BUILD_LINK" ]]; then
        rm -rf "$BUILD_LINK"
    fi
    ln -sfn "$BUILD_TARGET" "$BUILD_LINK"
}

verify_manifest_assets
link_build_directory

if [[ -L "$BUILD_LINK" ]]; then
    RESOLVED="$(readlink -f "$BUILD_LINK" 2>/dev/null || readlink "$BUILD_LINK")"
    echo "Vite build OK — $BUILD_LINK -> $RESOLVED"
else
    echo "ERROR: Failed to create build symlink at $BUILD_LINK"
    exit 1
fi
