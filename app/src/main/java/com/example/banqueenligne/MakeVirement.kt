package com.example.banqueenligne

import android.os.Bundle
import android.view.Window
import androidx.appcompat.app.AppCompatActivity


class MakeVirement : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.virement)
    }
}