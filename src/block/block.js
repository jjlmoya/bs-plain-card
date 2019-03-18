/**
 * BLOCK: bs-plain-card
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {SelectControl, TextControl} = wp.components;
const {withSelect} = wp.data;


registerBlockType('bonseo/block-bs-plain-card', {
	title: __('Plain Card'),
	icon: 'editor-quote',
	category: 'bonseo-blocks',
	keywords: [
		__('bs-banner-plain-card'),
		__('BonSeo'),
		__('BonSeo Block'),
	],
	edit: withSelect((select) => {
		const {getPostTypes} = select('core');
		return {
			types: getPostTypes(),
		};
	})(function (props) {
		const {attributes, className, setAttributes} = props;
		var types = props.types;
		if (!props.types) {
			return "Loading...";
		}
		return (
			<div>
				<h2>Plain Card</h2>
				<TextControl
					className={`${className}__max_entries`}
					label={__('Cuántas entradas:')}
					type="number"
					value={attributes.max_entries}
					onChange={max_entries => setAttributes({max_entries})}
				/>
				<SelectControl
					label="Tipo de Post"
					className={`${className}__type`}
					value={attributes.type}
					options={types.map((type) => {
						return {
							label: type.name,
							value: type.slug
						}
					})}
					onChange={type => setAttributes({type})}
				/>
			</div>
		);
	}),
	save: function () {
		return null;
	}
})
;
