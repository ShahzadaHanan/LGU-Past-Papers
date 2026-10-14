// Self-check for the LGU SGPA/CGPA math in public/assets/js/site.js.
// Run: node tests/gpa_calculator_check.js  (no dependencies)
// Expected values are the LGU Student Handbook Vol. II worked examples.
'use strict';
const assert = require('assert');
const fs = require('fs');
const path = require('path');
const vm = require('vm');

const window = {};
vm.runInNewContext(
    fs.readFileSync(path.join(__dirname, '../public/assets/js/site.js'), 'utf8'),
    { window, document: { addEventListener() {} } }
);
const gpa = window.lguGpa.gpaHundredths;

// Semester 1: B(4), A-(3), B+(3), W(excluded), F(2) -> 33 / 12 = 2.75
assert.strictEqual(gpa(300 * 4 + 370 * 3 + 330 * 3 + 0 * 2, 12), 275);
// Semester 2: 45 / 15 = 3.00
assert.strictEqual(gpa(4500, 15), 300);
// CGPA: (33 + 45) / 27 = 2.888.. -> 2.89
assert.strictEqual(gpa(3300 + 4500, 27), 289);
// Handbook rounding rule: 2.666667 -> 2.67, exact half rounds up (2.875 -> 2.88)
assert.strictEqual(gpa(800, 3), 267);
assert.strictEqual(gpa(575, 2), 288);
// No credits -> no GPA
assert.strictEqual(gpa(0, 0), null);

console.log('gpa_calculator_check: all assertions passed');
