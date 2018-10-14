module.exports = {
  verbose: true,
  "moduleFileExtensions": [
    "js",
    "json",
    "vue"
  ],
  "transform": {
    "^.+\\.vue$": "vue-jest",
    "^.+\\.js$": "babel-jest"
  },
  collectCoverage: true,
  collectCoverageFrom: [
    "resources/js/**/*.{js}",
    "!**/node_modules/**",
    "!**/vendor/**"
  ],
  coverageReporters: ['text', 'html', 'lcov'],
  coverageDirectory: './client-coverage'
};
