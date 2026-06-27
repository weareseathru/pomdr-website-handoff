/**
 * Adopt page: client-side search, category filter, and sort over the
 * server-rendered dog cards. Progressive enhancement only. The grid itself is
 * rendered from the editable pets CPT in page-adopt.php; this script never
 * creates or fetches dog data, it only shows, hides, and reorders the
 * .dog-card-link nodes already in the DOM.
 *
 * If the toolbar elements are absent, it does nothing.
 */
( function () {
  'use strict';

  var grid = document.getElementById( 'dogs-grid' );
  var searchInput = document.getElementById( 'search-input' );
  var filtersEl = document.getElementById( 'filters' );
  var sortSelect = document.getElementById( 'sort-select' );
  var resultsCount = document.getElementById( 'results-count' );

  if ( ! grid ) {
    return;
  }

  // All cards, plus their original DOM order for the "default" sort.
  var cards = Array.prototype.slice.call( grid.querySelectorAll( '.dog-card-link' ) );
  if ( ! cards.length ) {
    return;
  }
  var originalOrder = cards.slice();

  var state = {
    search: '',
    filter: 'all',
    sort: 'default'
  };

  function num( value ) {
    var n = parseFloat( value );
    return isNaN( n ) ? null : n;
  }

  // A card is visible when it matches both the active category and the search.
  function matches( card ) {
    if ( state.filter !== 'all' ) {
      var status = ( card.getAttribute( 'data-status' ) || '' ).split( /\s+/ );
      if ( status.indexOf( state.filter ) === -1 ) {
        return false;
      }
    }
    if ( state.search ) {
      var q = state.search;
      var name = ( card.getAttribute( 'data-name' ) || '' ).toLowerCase();
      var breed = ( card.getAttribute( 'data-breed' ) || '' ).toLowerCase();
      if ( name.indexOf( q ) === -1 && breed.indexOf( q ) === -1 ) {
        return false;
      }
    }
    return true;
  }

  // Order the cards for the current sort. "default" restores original order.
  function ordered() {
    if ( state.sort === 'default' ) {
      return originalOrder.slice();
    }
    var key = state.sort.indexOf( 'age' ) === 0 ? 'data-age' : 'data-weight';
    var dir = state.sort.indexOf( 'asc' ) !== -1 ? 1 : -1;
    var list = cards.slice();
    list.sort( function ( a, b ) {
      var av = num( a.getAttribute( key ) );
      var bv = num( b.getAttribute( key ) );
      // Cards with no value for the sort key sink to the bottom.
      if ( av === null && bv === null ) { return 0; }
      if ( av === null ) { return 1; }
      if ( bv === null ) { return -1; }
      return ( av - bv ) * dir;
    } );
    return list;
  }

  function apply() {
    var order = ordered();
    var visible = 0;

    order.forEach( function ( card ) {
      // Re-append in sorted order so the DOM reflects the chosen sort.
      grid.appendChild( card );
      if ( matches( card ) ) {
        card.style.display = '';
        visible++;
      } else {
        card.style.display = 'none';
      }
    } );

    if ( resultsCount ) {
      resultsCount.textContent = visible === 1 ? '1 dog' : visible + ' dogs';
    }
  }

  if ( searchInput ) {
    searchInput.addEventListener( 'input', function ( e ) {
      state.search = e.target.value.trim().toLowerCase();
      apply();
    } );
  }

  if ( filtersEl ) {
    filtersEl.addEventListener( 'click', function ( e ) {
      var btn = e.target.closest( '.filter' );
      if ( ! btn || ! filtersEl.contains( btn ) ) {
        return;
      }
      state.filter = btn.getAttribute( 'data-filter' ) || 'all';
      filtersEl.querySelectorAll( '.filter' ).forEach( function ( b ) {
        b.classList.toggle( 'active', b === btn );
      } );
      apply();
    } );
  }

  if ( sortSelect ) {
    sortSelect.addEventListener( 'change', function ( e ) {
      state.sort = e.target.value;
      apply();
    } );
  }
}() );
