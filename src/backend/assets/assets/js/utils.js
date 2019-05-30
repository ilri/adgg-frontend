/**
 * strtr() for JavaScript
 * Translate characters or replace substrings
 *
 * @author Dmitry Sheiko
 * @version strtr.js, v 1.0.1
 * @license MIT
 * @copyright (c) Dmitry Sheiko http://dsheiko.com
 **/

String.prototype.strtr = function ( dic ) {
    const str = this.toString(),
        makeToken = ( inx ) => `{{###~${ inx }~###}}`,

        tokens = Object.keys( dic )
            .map( ( key, inx ) => ({
                key,
                val: dic[ key ],
                token: makeToken( inx )
            })),

        tokenizedStr = tokens.reduce(( carry, entry ) =>
            carry.replace( entry.key, entry.token ), str );

    return tokens.reduce(( carry, entry ) =>
        carry.replace( entry.token, entry.val ), tokenizedStr );
};