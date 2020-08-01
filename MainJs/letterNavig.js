let createArrayAtoZ = _ => {
    return Array 
       .apply(null, {length: 26}) 
       .map((x, i) => String.fromCharCode(65 + i));
}

let jumptoAnchor = anchor => {
    window.location.href = "#" + anchor; 
    if ((window.innerHeight + window.scrollY) < document.body.offsetHeight) 
    {
        window.scrollBy(0,-65);
    }
}

let createNavigationList = _ => {
    const abcChars = createArrayAtoZ();
    const navigationEntries = abcChars.reduce(createDivForCharElement, ''); 
    
    $('#nav').append(navigationEntries);

    abcChars.forEach(letter => { 
            changeItemState(letter); 
        });
}

function changeItemState ( character ) 
{
    const characterElement = $('#nav').find('.CharacterElement[data-filter="' + character + '"]');
    $(characterElement).click(() => jumptoAnchor(character));
    characterElement.removeClass('Inactive');
}

let createDivForCharElement = (block, charToAdd) => { 
    return block + "<div id='CharacterElement' class='CharacterElement' data-filter='" + charToAdd + "'>" + charToAdd + "</div>"; 
}
