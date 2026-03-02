<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Drawing;

abstract class Named_Color
{
    public const ALICE_BLUE = ["Name" => "AliceBlue", "Hex" => "#F0F8FF", "RGB" => "rgb(240, 248, 255)"];
    public const ANTIQUE_WHITE = ["Name" => "AntiqueWhite", "Hex" => "#FAEBD7", "RGB" => "rgb(250, 235, 215)"];
    public const AQUA = ["Name" => "Aqua", "Hex" => "#00FFFF", "RGB" => "rgb(0, 255, 255)"];
    public const AQUAMARINE = ["Name" => "Aquamarine", "Hex" => "#7FFFD4", "RGB" => "rgb(127, 255, 212)"];
    public const AZURE = ["Name" => "Azure", "Hex" => "#F0FFFF", "RGB" => "rgb(240, 255, 255)"];
    public const BEIGE = ["Name" => "Beige", "Hex" => "#F5F5DC", "RGB" => "rgb(245, 245, 220)"];
    public const BISQUE = ["Name" => "Bisque", "Hex" => "#FFE4C4", "RGB" => "rgb(255, 228, 196)"];
    public const BLACK = ["Name" => "Black", "Hex" => "#000000", "RGB" => "rgb(0, 0, 0)"];
    public const BLANCHED_ALMOND = ["Name" => "BlanchedAlmond", "Hex" => "#FFEBCD", "RGB" => "rgb(255, 235, 205)"];
    public const BLUE = ["Name" => "Blue", "Hex" => "#0000FF", "RGB" => "rgb(0, 0, 255)"];
    public const BLUE_VIOLET = ["Name" => "BlueViolet", "Hex" => "#8A2BE2", "RGB" => "rgb(138, 43, 226)"];
    public const BROWN = ["Name" => "Brown", "Hex" => "#A52A2A", "RGB" => "rgb(165, 42, 42)"];
    public const BURLYWOOD = ["Name" => "Burlywood", "Hex" => "#DEB887", "RGB" => "rgb(222, 184, 135)"];
    public const CADET_BLUE = ["Name" => "CadetBlue", "Hex" => "#5F9EA0", "RGB" => "rgb(95, 158, 160)"];
    public const CHARTREUSE = ["Name" => "Chartreuse", "Hex" => "#7FFF00", "RGB" => "rgb(127, 255, 0)"];
    public const CHOCOLATE = ["Name" => "Chocolate", "Hex" => "#D2691E", "RGB" => "rgb(210, 105, 30)"];
    public const CORAL = ["Name" => "Coral", "Hex" => "#FF7F50", "RGB" => "rgb(255, 127, 80)"];
    public const CORNFLOWER_BLUE = ["Name" => "CornflowerBlue", "Hex" => "#6495ED", "RGB" => "rgb(100, 149, 237)"];
    public const CORNSILK = ["Name" => "Cornsilk", "Hex" => "#FFF8DC", "RGB" => "rgb(255, 248, 220)"];
    public const CRIMSON = ["Name" => "Crimson", "Hex" => "#DC143C", "RGB" => "rgb(220, 20, 60)"];
    public const CYAN = ["Name" => "Cyan", "Hex" => "#00FFFF", "RGB" => "rgb(0, 255, 255)"];
    public const DARK_BLUE = ["Name" => "DarkBlue", "Hex" => "#00008B", "RGB" => "rgb(0, 0, 139)"];
    public const DARK_CYAN = ["Name" => "DarkCyan", "Hex" => "#008B8B", "RGB" => "rgb(0, 139, 139)"];
    public const DARK_GOLDENROD = ["Name" => "DarkGoldenrod", "Hex" => "#B8860B", "RGB" => "rgb(184, 134, 11)"];
    public const DARK_GRAY = ["Name" => "DarkGray", "Hex" => "#A9A9A9", "RGB" => "rgb(169, 169, 169)"];
    public const DARK_GREEN = ["Name" => "DarkGreen", "Hex" => "#006400", "RGB" => "rgb(0, 100, 0)"];
    public const DARK_KHAKI = ["Name" => "DarkKhaki", "Hex" => "#BDB76B", "RGB" => "rgb(189, 183, 107)"];
    public const DARK_MAGENTA = ["Name" => "DarkMagenta", "Hex" => "#8B008B", "RGB" => "rgb(139, 0, 139)"];
    public const DARK_OLIVE_GREEN = ["Name" => "DarkOliveGreen", "Hex" => "#556B2F", "RGB" => "rgb(85, 107, 47)"];
    public const DARK_ORANGE = ["Name" => "DarkOrange", "Hex" => "#FF8C00", "RGB" => "rgb(255, 140, 0)"];
    public const DARK_ORCHID = ["Name" => "DarkOrchid", "Hex" => "#9932CC", "RGB" => "rgb(153, 50, 204)"];
    public const DARK_YELLOW = ["Name" => "DarkYellow", "Hex" => "#8B8000", "RGB" => "rgb(139, 128, 0)"];
    public const DARK_RED = ["Name" => "DarkRed", "Hex" => "#8B0000", "RGB" => "rgb(139, 0, 0)"];
    public const DARK_SALMON = ["Name" => "DarkSalmon", "Hex" => "#E9967A", "RGB" => "rgb(233, 150, 122)"];
    public const DARK_SEA_GREEN = ["Name" => "DarkSeaGreen", "Hex" => "#8FBC8F", "RGB" => "rgb(143, 188, 143)"];
    public const DARK_SLATE_BLUE = ["Name" => "DarkSlateBlue", "Hex" => "#483D8B", "RGB" => "rgb(72, 61, 139)"];
    public const DARK_SLATE_GRAY = ["Name" => "DarkSlateGray", "Hex" => "#2F4F4F", "RGB" => "rgb(47, 79, 79)"];
    public const DARK_TURQUOISE = ["Name" => "DarkTurquoise", "Hex" => "#00CED1", "RGB" => "rgb(0, 206, 209)"];
    public const DARK_VIOLET = ["Name" => "DarkViolet", "Hex" => "#9400D3", "RGB" => "rgb(148, 0, 211)"];
    public const DEEP_PINK = ["Name" => "DeepPink", "Hex" => "#FF1493", "RGB" => "rgb(255, 20, 147)"];
    public const DEEP_SKY_BLUE = ["Name" => "DeepSkyBlue", "Hex" => "#00BFFF", "RGB" => "rgb(0, 191, 255)"];
    public const DIM_GRAY = ["Name" => "DimGray", "Hex" => "#696969", "RGB" => "rgb(105, 105, 105)"];
    public const DODGER_BLUE = ["Name" => "DodgerBlue", "Hex" => "#1E90FF", "RGB" => "rgb(30, 144, 255)"];
    public const FIREBRICK = ["Name" => "Firebrick", "Hex" => "#B22222", "RGB" => "rgb(178, 34, 34)"];
    public const FLORAL_WHITE = ["Name" => "FloralWhite", "Hex" => "#FFFAF0", "RGB" => "rgb(255, 250, 240)"];
    public const FOREST_GREEN = ["Name" => "ForestGreen", "Hex" => "#228B22", "RGB" => "rgb(34, 139, 34)"];
    public const FUCHSIA = ["Name" => "Fuchsia", "Hex" => "#FF00FF", "RGB" => "rgb(255, 0, 255)"];
    public const GAINSBORO = ["Name" => "Gainsboro", "Hex" => "#DCDCDC", "RGB" => "rgb(220, 220, 220)"];
    public const GHOST_WHITE = ["Name" => "GhostWhite", "Hex" => "#F8F8FF", "RGB" => "rgb(248, 248, 255)"];
    public const GOLD = ["Name" => "Gold", "Hex" => "#FFD700", "RGB" => "rgb(255, 215, 0)"];
    public const GOLDENROD = ["Name" => "Goldenrod", "Hex" => "#DAA520", "RGB" => "rgb(218, 165, 32)"];
    public const GRAY = ["Name" => "Gray", "Hex" => "#808080", "RGB" => "rgb(128, 128, 128)"];
    public const GREEN = ["Name" => "Green", "Hex" => "#008000", "RGB" => "rgb(0, 128, 0)"];
    public const GREEN_YELLOW = ["Name" => "GreenYellow", "Hex" => "#ADFF2F", "RGB" => "rgb(173, 255, 47)"];
    public const HONEYDEW = ["Name" => "Honeydew", "Hex" => "#F0FFF0", "RGB" => "rgb(240, 255, 240)"];
    public const HOT_PINK = ["Name" => "HotPink", "Hex" => "#FF69B4", "RGB" => "rgb(255, 105, 180)"];
    public const INDIAN_RED = ["Name" => "IndianRed", "Hex" => "#CD5C5C", "RGB" => "rgb(205, 92, 92)"];
    public const INDIGO = ["Name" => "Indigo", "Hex" => "#4B0082", "RGB" => "rgb(75, 0, 130)"];
    public const IVORY = ["Name" => "Ivory", "Hex" => "#FFFFF0", "RGB" => "rgb(255, 255, 240)"];
    public const KHAKI = ["Name" => "Khaki", "Hex" => "#F0E68C", "RGB" => "rgb(240, 230, 140)"];
    public const LAVENDER = ["Name" => "Lavender", "Hex" => "#E6E6FA", "RGB" => "rgb(230, 230, 250)"];
    public const LAVENDER_BLUSH = ["Name" => "LavenderBlush", "Hex" => "#FFF0F5", "RGB" => "rgb(255, 240, 245)"];
    public const LAWN_GREEN = ["Name" => "LawnGreen", "Hex" => "#7CFC00", "RGB" => "rgb(124, 252, 0)"];
    public const LEMON_CHIFFON = ["Name" => "LemonChiffon", "Hex" => "#FFFACD", "RGB" => "rgb(255, 250, 205)"];
    public const LIGHT_BLUE = ["Name" => "LightBlue", "Hex" => "#ADD8E6", "RGB" => "rgb(173, 216, 230)"];
    public const LIGHT_CORAL = ["Name" => "LightCoral", "Hex" => "#F08080", "RGB" => "rgb(240, 128, 128)"];
    public const LIGHT_CYAN = ["Name" => "LightCyan", "Hex" => "#E0FFFF", "RGB" => "rgb(224, 255, 255)"];
    public const LIGHT_GOLDENROD_YELLOW = ["Name" => "LightGoldenrodYellow", "Hex" => "#FAFAD2", "RGB" => "rgb(250, 250, 210)"];
    public const LIGHT_GRAY = ["Name" => "LightGray", "Hex" => "#D3D3D3", "RGB" => "rgb(211, 211, 211)"];
    public const LIGHT_GREEN = ["Name" => "LightGreen", "Hex" => "#90EE90", "RGB" => "rgb(144, 238, 144)"];
    public const LIGHT_PINK = ["Name" => "LightPink", "Hex" => "#FFB6C1", "RGB" => "rgb(255, 182, 193)"];
    public const LIGHT_SALMON = ["Name" => "LightSalmon", "Hex" => "#FFA07A", "RGB" => "rgb(255, 160, 122)"];
    public const LIGHT_SEA_GREEN = ["Name" => "LightSeaGreen", "Hex" => "#20B2AA", "RGB" => "rgb(32, 178, 170)"];
    public const LIGHT_SKY_BLUE = ["Name" => "LightSkyBlue", "Hex" => "#87CEFA", "RGB" => "rgb(135, 206, 250)"];
    public const LIGHT_SLATE_GRAY = ["Name" => "LightSlateGray", "Hex" => "#778899", "RGB" => "rgb(119, 136, 153)"];
    public const LIGHT_STEEL_BLUE = ["Name" => "LightSteelBlue", "Hex" => "#B0C4DE", "RGB" => "rgb(176, 196, 222)"];
    public const LIGHT_YELLOW = ["Name" => "LightYellow", "Hex" => "#FFFFE0", "RGB" => "rgb(255, 255, 224)"];
    public const LIME = ["Name" => "Lime", "Hex" => "#00FF00", "RGB" => "rgb(0, 255, 0)"];
    public const LIME_GREEN = ["Name" => "LimeGreen", "Hex" => "#32CD32", "RGB" => "rgb(50, 205, 50)"];
    public const LINEN = ["Name" => "Linen", "Hex" => "#FAF0E6", "RGB" => "rgb(250, 240, 230)"];
    public const MAGENTA = ["Name" => "Magenta", "Hex" => "#FF00FF", "RGB" => "rgb(255, 0, 255)"];
    public const MAROON = ["Name" => "Maroon", "Hex" => "#800000", "RGB" => "rgb(128, 0, 0)"];
    public const MEDIUM_AQUAMARINE = ["Name" => "MediumAquamarine", "Hex" => "#66CDAA", "RGB" => "rgb(102, 205, 170)"];
    public const MEDIUM_BLUE = ["Name" => "MediumBlue", "Hex" => "#0000CD", "RGB" => "rgb(0, 0, 205)"];
    public const MEDIUM_ORCHID = ["Name" => "MediumOrchid", "Hex" => "#BA55D3", "RGB" => "rgb(186, 85, 211)"];
    public const MEDIUM_PURPLE = ["Name" => "MediumPurple", "Hex" => "#9370DB", "RGB" => "rgb(147, 112, 219)"];
    public const MEDIUM_SEA_GREEN = ["Name" => "MediumSeaGreen", "Hex" => "#3CB371", "RGB" => "rgb(60, 179, 113)"];
    public const MEDIUM_SLATE_BLUE = ["Name" => "MediumSlateBlue", "Hex" => "#7B68EE", "RGB" => "rgb(123, 104, 238)"];
    public const MEDIUM_SPRING_GREEN = ["Name" => "MediumSpringGreen", "Hex" => "#00FA9A", "RGB" => "rgb(0, 250, 154)"];
    public const MEDIUM_TURQUOISE = ["Name" => "MediumTurquoise", "Hex" => "#48D1CC", "RGB" => "rgb(72, 209, 204)"];
    public const MEDIUM_VIOLET_RED = ["Name" => "MediumVioletRed", "Hex" => "#C71585", "RGB" => "rgb(199, 21, 133)"];
    public const MIDNIGHT_BLUE = ["Name" => "MidnightBlue", "Hex" => "#191970", "RGB" => "rgb(25, 25, 112)"];
    public const MINT_CREAM = ["Name" => "MintCream", "Hex" => "#F5FFFA", "RGB" => "rgb(245, 255, 250)"];
    public const MISTY_ROSE = ["Name" => "MistyRose", "Hex" => "#FFE4E1", "RGB" => "rgb(255, 228, 225)"];
    public const MOCCASIN = ["Name" => "Moccasin", "Hex" => "#FFE4B5", "RGB" => "rgb(255, 228, 181)"];
    public const NAVY = ["Name" => "Navy", "Hex" => "#000080", "RGB" => "rgb(0, 0, 128)"];
    public const OLD_LACE = ["Name" => "OldLace", "Hex" => "#FDF5E6", "RGB" => "rgb(253, 245, 230)"];
    public const OLIVE = ["Name" => "Olive", "Hex" => "#808000", "RGB" => "rgb(128, 128, 0)"];
    public const OLIVE_DRAB = ["Name" => "OliveDrab", "Hex" => "#6B8E23", "RGB" => "rgb(107, 142, 35)"];
    public const ORANGE = ["Name" => "Orange", "Hex" => "#FFA500", "RGB" => "rgb(255, 165, 0)"];
    public const ORANGE_RED = ["Name" => "OrangeRed", "Hex" => "#FF4500", "RGB" => "rgb(255, 69, 0)"];
    public const ORCHID = ["Name" => "Orchid", "Hex" => "#DA70D6", "RGB" => "rgb(218, 112, 214)"];
    public const PALE_GOLDENROD = ["Name" => "PaleGoldenrod", "Hex" => "#EEE8AA", "RGB" => "rgb(238, 232, 170)"];
    public const PALE_GREEN = ["Name" => "PaleGreen", "Hex" => "#98FB98", "RGB" => "rgb(152, 251, 152)"];
    public const PALE_TURQUOISE = ["Name" => "PaleTurquoise", "Hex" => "#AFEEEE", "RGB" => "rgb(175, 238, 238)"];
    public const PALE_VIOLET_RED = ["Name" => "PaleVioletRed", "Hex" => "#DB7093", "RGB" => "rgb(219, 112, 147)"];
    public const PAPAYA_WHIP = ["Name" => "PapayaWhip", "Hex" => "#FFEFD5", "RGB" => "rgb(255, 239, 213)"];
    public const PEACH_PUFF = ["Name" => "PeachPuff", "Hex" => "#FFDAB9", "RGB" => "rgb(255, 218, 185)"];
    public const PERU = ["Name" => "Peru", "Hex" => "#CD853F", "RGB" => "rgb(205, 133, 63)"];
    public const PINK = ["Name" => "Pink", "Hex" => "#FFC0CB", "RGB" => "rgb(255, 192, 203)"];
    public const PLUM = ["Name" => "Plum", "Hex" => "#DDA0DD", "RGB" => "rgb(221, 160, 221)"];
    public const POWDER_BLUE = ["Name" => "PowderBlue", "Hex" => "#B0E0E6", "RGB" => "rgb(176, 224, 230)"];
    public const PURPLE = ["Name" => "Purple", "Hex" => "#800080", "RGB" => "rgb(128, 0, 128)"];
    public const RED = ["Name" => "Red", "Hex" => "#FF0000", "RGB" => "rgb(255, 0, 0)"];
    public const ROSY_BROWN = ["Name" => "RosyBrown", "Hex" => "#BC8F8F", "RGB" => "rgb(188, 143, 143)"];
    public const ROYAL_BLUE = ["Name" => "RoyalBlue", "Hex" => "#4169E1", "RGB" => "rgb(65, 105, 225)"];
    public const SADDLE_BROWN = ["Name" => "SaddleBrown", "Hex" => "#8B4513", "RGB" => "rgb(139, 69, 19)"];
    public const SALMON = ["Name" => "Salmon", "Hex" => "#FA8072", "RGB" => "rgb(250, 128, 114)"];
    public const SANDY_BROWN = ["Name" => "SandyBrown", "Hex" => "#F4A460", "RGB" => "rgb(244, 164, 96)"];
    public const SEA_GREEN = ["Name" => "SeaGreen", "Hex" => "#2E8B57", "RGB" => "rgb(46, 139, 87)"];
    public const SIENNA = ["Name" => "Sienna", "Hex" => "#A0522D", "RGB" => "rgb(160, 82, 45)"];
    public const SILVER = ["Name" => "Silver", "Hex" => "#C0C0C0", "RGB" => "rgb(192, 192, 192)"];
    public const SKY_BLUE = ["Name" => "SkyBlue", "Hex" => "#87CEEB", "RGB" => "rgb(135, 206, 235)"];
    public const SLATE_BLUE = ["Name" => "SlateBlue", "Hex" => "#6A5ACD", "RGB" => "rgb(106, 90, 205)"];
    public const SLATE_GRAY = ["Name" => "SlateGray", "Hex" => "#708090", "RGB" => "rgb(112, 128, 144)"];
    public const SNOW = ["Name" => "Snow", "Hex" => "#FFFAFA", "RGB" => "rgb(255, 250, 250)"];
    public const SPRING_GREEN = ["Name" => "SpringGreen", "Hex" => "#00FF7F", "RGB" => "rgb(0, 255, 127)"];
    public const STEEL_BLUE = ["Name" => "SteelBlue", "Hex" => "#4682B4", "RGB" => "rgb(70, 130, 180)"];
    public const TAN = ["Name" => "Tan", "Hex" => "#D2B48C", "RGB" => "rgb(210, 180, 140)"];
    public const TEAL = ["Name" => "Teal", "Hex" => "#008080", "RGB" => "rgb(0, 128, 128)"];
    public const THISTLE = ["Name" => "Thistle", "Hex" => "#D8BFD8", "RGB" => "rgb(216, 191, 216)"];
    public const TOMATO = ["Name" => "Tomato", "Hex" => "#FF6347", "RGB" => "rgb(255, 99, 71)"];
    public const TURQUOISE = ["Name" => "Turquoise", "Hex" => "#40E0D0", "RGB" => "rgb(64, 224, 208)"];
    public const VIOLET = ["Name" => "Violet", "Hex" => "#EE82EE", "RGB" => "rgb(238, 130, 238)"];
    public const WHEAT = ["Name" => "Wheat", "Hex" => "#F5DEB3", "RGB" => "rgb(245, 222, 179)"];
    public const WHITE = ["Name" => "White", "Hex" => "#FFFFFF", "RGB" => "rgb(255, 255, 255)"];
    public const YELLOW = ["Name" => "Yellow", "Hex" => "#FFFF00", "RGB" => "rgb(255, 255, 0)"];
    public const YELLOW_GREEN = ["Name" => "YellowGreen", "Hex" => "#9ACD32", "RGB" => "rgb(154, 205, 50)"];
}
