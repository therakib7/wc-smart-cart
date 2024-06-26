module.exports = {
  "extends": [
    "plugin:@wordpress/eslint-plugin/recommended"
  ],
  "rules": {
    "prettier/prettier": [
      "error",
      {
        "endOfLine": "auto",
        "parenSpacing": true,
        "tabWidth": 4,
        "useTabs": false,
        "singleQuote": true,
        "trailingComma": "es5",
        "bracketSpacing": true,
        "jsxBracketSameLine": false,
        "semi": true,
        "arrowParens": "always"
      }
    ],
    "@wordpress/i18n-text-domain": [
      "error",
      {
        "allowedTextDomain": [
          "wc-smart-cart"
        ]
      }
    ]
  }
}