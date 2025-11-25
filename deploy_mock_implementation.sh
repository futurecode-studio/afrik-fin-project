#!/bin/bash
# Script to resolve git push issue and commit mock data implementation

set -e

cd /var/www/html/freelance/afri-fin-project

echo "[1] Configuring git to disable pager..."
git config --local core.pager cat
git config --local pager.log false

echo "[2] Checking current status..."
GIT_PAGER=cat git status --short

echo "[3] Fetching latest from remote..."
GIT_PAGER=cat git fetch origin

echo "[4] Pulling latest changes..."
GIT_PAGER=cat git pull origin master --rebase || true

echo "[5] Staging files..."
git add -A

echo "[6] Creating commit..."
git commit -m "feat: Implement mock mutual funds with daily realistic variations

- Added getMockMutualFunds() method with deterministic daily variations
- Refactored getDefaultMutualFunds() as final static fallback
- Updated getMutualFunds() fallback chain for offline environments
- Added MUTUAL_FUNDS_USE_MOCK env flag (enabled by default)
- Added MOCK_DATA_IMPLEMENTATION.md documentation
- Added MOCK_IMPLEMENTATION_SESSION_LOG.md session notes

Features:
- Generates 8 funds with formula-based variations (-2% to +2%)
- Changes daily based on calendar date (deterministic)
- Zero network calls required (offline-safe)
- Backward compatible with existing UI
- Fully configurable via environment variables

Fixes: Replace static 0.00 values with realistic mock data" || true

echo "[7] Pushing to remote..."
GIT_PAGER=cat git push origin master

echo "[DONE] Successfully pushed mock implementation to remote!"
