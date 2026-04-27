## Week 1 — Fundamentals & Structure

### Goal (from plan)

Strengthen core frontend fundamentals by building **clean, structured, responsive** UI layouts using semantic HTML and modern CSS.

### Deliverables

- **Two responsive UI screens**
  - Screen 1: **Analytics Dashboard**
  - Screen 2: **Authentication (Sign in)**
- **Shared design system**
  - CSS variables (colors, spacing, typography)
  - Reusable layout utilities
  - Component-style class naming (BEM-ish)

### Folder structure

```text
week-1/
  src/
    assets/
    pages/
      screen-1.html
      screen-2.html
    styles/
      base.css
      components.css
      screen-1.css
      screen-2.css
```

### How to run

Open the HTML files directly, or run a local server:

```bash
cd "week-1"
python3 -m http.server 5500
```

Then:
- `http://localhost:5500/src/pages/screen-1.html`
- `http://localhost:5500/src/pages/screen-2.html`

### What to look for (supervisor checklist)

- **HTML semantics**: headings, landmarks (`header`, `nav`, `main`, `section`, `aside`, `footer`)
- **Layout structuring**: responsive grids, consistent spacing, no “magic numbers”
- **Readability**: naming conventions, grouping, predictable file structure
- **Responsiveness**: mobile-first, breakpoints, fluid typography, accessible tap targets

