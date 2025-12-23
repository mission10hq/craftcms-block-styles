# GitHub Copilot Code Review Instructions

## Review Philosophy
- Only comment when you have HIGH CONFIDENCE (>80%) that an issue exists
- Be concise: one sentence per comment when possible
- Focus on actionable feedback, not observations
- When reviewing template code, only comment on clarity issues if the markup is genuinely confusing or could cause rendering bugs. "Could be cleaner" is not the same as "is broken" - stay silent unless HIGH confidence it will cause problems

## Priority Areas (Review These)

### Security & Safety
- XSS vulnerabilities: unescaped output (missing `|e` filter on user content)
- SQL injection via raw queries (should use Craft's query builder)
- Path traversal in template includes or asset paths
- Hardcoded credentials or API keys
- CSRF protection missing on forms
- Unsafe `|raw` filter usage without sanitization

### Correctness Issues
- Logic errors in Twig conditionals that could break rendering
- Undefined variable access without null checks (`variable` vs `variable ?? null`)
- Missing `ignore missing` on dynamic includes that could 500
- Incorrect Craft CMS API usage (deprecated methods, wrong parameters)
- Alpine.js reactivity bugs (missing `$nextTick`, wrong `x-` directive usage)
- Tailwind v4 syntax errors (using old v3 patterns like `theme()`)
- Asset/image rendering without `craft.images.render()` (loses optimization)
- Content block templates not following the `blocktype_style.twig` naming convention

### Architecture & Patterns
- Components not using `<x-component-name>` syntax when they should
- Inline styles instead of Tailwind utility classes
- Breaking the content block rendering cascade (style fallback logic)
- Buttons not using the native Link field pattern (`:label` attribute)
- Missing wrapper component on content blocks
- Computed logic in markup instead of `{% set %}` blocks

## Project-Specific Context

- **Craft CMS 5.8** on PHP 8.2 - use current APIs, not deprecated ones
- **Tailwind CSS v4** - CSS-first config in `src/css/tailwind-config.css`, use CSS variables not `theme()`
- **Twig Components** - `<x-component-name>` syntax with dynamic props via `:prop="value"`
- **Alpine.js 3.14** - available as `window.Alpine`, uses `x-data`, `x-intersect`, etc.
- **Content blocks** - stored in `templates/_partials/content-blocks/`, named `blocktype_style.twig`
- **Images** - always use `craft.images.render()` for optimization and WebP support
- **Buttons** - use `:label="link.getText()"` pattern with native Craft Link fields

### Twig Standards
- Single space after `{{`, `{%`, `{#` and before closing
- No spaces around `|`, `.`, `[]` operators
- Snake_case for variables and named parameters
- Use `:` not `=` for named arguments in components

## CI Pipeline Context

**Important**: You review PRs immediately, before CI completes. Do not flag issues that CI will catch.

### What Our CI Checks (`.github/workflows/ci.yml`)

**PHP/Composer:**
- `composer install --no-interaction --prefer-dist` - Dependency installation
- Includes `vendor/bin/twig-cs-fixer` for Twig linting

**Node/Vite:**
- `npm ci` - Fresh dependency install
- `npm run lint:css` - Stylelint for CSS/SCSS
- `npm run lint:js` - ESLint for JavaScript
- `npm run lint:twig` - twig-cs-fixer for template syntax
- `npm run build` - Vite production build

**Key insight**: Twig formatting issues, CSS violations, and JS linting errors will be caught by CI. Focus on logic and architecture issues that linters cannot detect.

## Skip These (Low Value)

Do not comment on:
- **Twig formatting** - CI handles this (twig-cs-fixer)
- **CSS/Tailwind formatting** - CI handles this (Stylelint)
- **JS formatting/style** - CI handles this (ESLint)
- **Build failures** - CI handles this (Vite build)
- **Missing dependencies** - CI handles this (npm ci/composer install)
- **Minor class ordering in Tailwind** - unless it affects specificity
- **Suggestions to add comments** - for self-documenting templates
- **Refactoring suggestions** - unless there's a clear bug or maintainability issue
- **Multiple issues in one comment** - choose the single most critical issue
- **Whitespace or indentation** - linters handle this
- **Pedantic Twig style preferences** - unless it violates documented standards

## Response Format

When you identify an issue:
1. **State the problem** (1 sentence)
2. **Why it matters** (1 sentence, only if not obvious)
3. **Suggested fix** (code snippet or specific action)

Example:
```
This outputs user content without escaping, creating XSS risk. Use `{{ userContent|e }}` instead.
```

Example:
```
Missing null check will cause a template error if `block.headline` is empty. Use `block.headline ?? ''`.
```

Example:
```
Image rendered without `craft.images.render()` loses WebP optimization and lazy loading.
```

## When to Stay Silent

If you're uncertain whether something is an issue, don't comment. False positives create noise and reduce trust in the review process.

Specifically stay silent on:
- Template patterns that match existing codebase conventions
- Tailwind class choices that are stylistic preferences
- Alpine.js patterns that work correctly even if not "optimal"
- Twig logic that renders correctly even if verbose
