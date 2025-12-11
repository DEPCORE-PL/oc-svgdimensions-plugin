
---

# **📄 CHANGELOG.md**

```markdown
# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to **Semantic Versioning**.

---

## [1.0.1] - 2025-12-11
### Changed
- Improved SVG dimension parsing logic.
- Casted all returned dimensions to integers.
- Updated Twig helper behavior for cleaner output.

---

## [1.0.0] - 2025-12-11
### Added
- Initial plugin release.
- Added SVG dimension parser (`width`, `height`, `viewBox` support).
- Added Twig helpers:
  - `svg_dimensions(file)`
  - `image_dimensions(file)`
- Full Tailor compatibility.
- No database writes or schema changes.

---

## [Unreleased]
### Planned
- Option to enable caching of parsed dimensions.
- Optional fallback scaling from `<svg style="...">`.
- Add tests for malformed SVG cases.
