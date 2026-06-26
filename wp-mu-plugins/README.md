# wp-mu-plugins

Must-use plugins for the POMDR Local/WordPress site. Deploy to
`wp-content/mu-plugins/` on the WordPress install.

- **pomdr-mcp-abilities.php** — registers POMDR content abilities (list/get/create/
  update dogs, list events) and exposes them as tools on the mcp-adapter default
  MCP server, so the WordPress MCP can read and manage content. Requires the
  WordPress/mcp-adapter plugin active. No delete abilities; writes need edit_posts.
