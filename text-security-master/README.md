# text-security
Cross-browser alternative to `-webkit-text-security`

This is a simple set of fonts that only consists of 3 different characters.
Disc <img
src="https://cdn.rawgit.com/noppa/text-security/master/assets/disc.svg"
width="5px"> circle <img
src="https://cdn.rawgit.com/noppa/text-security/master/assets/circle.svg"
width="10px"> and square <img
src="https://cdn.rawgit.com/noppa/text-security/master/assets/square.svg"
width="10px">. For example, setting  
`font-family: "text-security-circle"` for
an element should then display all the element's characters in a concealed
way, like it was a password field.

This is useful if you want to get the benefits of `input[type="password"]`
but also combine that with other element types, like `input[type="tel"]`. In
fact, the project was created for this exact purpose [as an answer to a
StackOverflow
question](https://stackoverflow.com/questions/36935576/how-to-make-input-type-tel-work-as-type-password/36950075#36950075).

## Installation
```
npm install text-security
```

You can use the fonts by adding this repo as a dependency and including
`dist/text-security.css` in your project, like so

`<link rel="stylesheet" type="text/css"
href="node_modules/text-security/dist/text-security.css">`

## Building with custom modifications
If you want to make your own tweaks, the `npm run build` command has two
optional arguments for you. By default, 768 different unicode characters are
included in the fonts, making it reliable for different use-cases but also
adding quite a big font files as a dependency. You can use the
`--max={number}` option to reduce the amount of included unicode characters.

If you are feeling wild, you can also add your custom shapes by dropping them
to *assets* folder and running the build with `--shapes={string}` option. The
value should be comma-separated list of svg file names (don't include the
file extension in the name). The default value is `circle,square,disc`.
The generated css will have class names with `text-security-*` prefix
followed by the name of the shape, like `text-security-disc`.

## Demo
*demo.html* contains a proof-of-concept demo file, which you can just open in
any browser. The same thing can also be found [as a fiddle](https://jsfiddle.net/449Lamue/6/).
